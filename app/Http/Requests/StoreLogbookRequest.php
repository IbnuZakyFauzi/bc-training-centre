<?php

namespace App\Http\Requests;

use App\Support\CompetencyScale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLogbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalisasi nilai item evaluasi menjadi integer 1-4 sebelum divalidasi.
     * Nilai legacy 'K' / 'BK' tetap diterima dan dikonversi otomatis, sedangkan
     * nilai di luar skala dibiarkan agar ditolak aturan validasi.
     */
    protected function prepareForValidation(): void
    {
        $payload = $this->input('sop_payload');

        if (is_array($payload)) {
            $this->merge(['sop_payload' => CompetencyScale::normalizePayload($payload, true)]);
        }
    }

    public function rules(): array
    {
        $isDraft = $this->action_type === 'draft';

        return [
            'date' => [$isDraft ? 'nullable' : 'required', 'date'],
            'shift' => [$isDraft ? 'nullable' : 'required', 'in:day,night'],
            'equipment_category_id' => [$isDraft ? 'nullable' : 'required', 'exists:equipment_categories,id'],
            'equipment_id' => ['nullable', 'exists:equipments,id'],
            'equipment_number' => [$isDraft ? 'nullable' : 'required', 'string', 'max:100'],
            'trainer_id' => ['nullable', 'exists:users,id'],
            'selected_pengawas_ids' => ['nullable', 'array', 'min:1'],
            'selected_pengawas_ids.*' => ['exists:users,id'],
            'selected_operator_pendamping_ids' => ['nullable', 'array', 'min:1'],
            'selected_operator_pendamping_ids.*' => ['exists:users,id'],
            'location' => [$isDraft ? 'nullable' : 'required', 'string', 'max:255'],
            'hm_start' => [$isDraft ? 'nullable' : 'required', 'numeric', 'min:0'],
            'hm_end' => [$isDraft ? 'nullable' : 'required', 'numeric', 'gte:hm_start'],
            'trainer_ratings' => ['nullable', 'array'],
            'trainer_ratings.*.user_id' => ['required_with:trainer_ratings', 'exists:users,id'],
            'trainer_ratings.*.rating' => ['required_with:trainer_ratings', 'integer', 'min:0', 'max:5'],
            'trainer_ratings.*.role_type' => ['nullable', 'string', 'in:instruktur,pengawas,operator_pendamping'],
            'sop_payload' => [$isDraft ? 'nullable' : 'required', 'array'],
            'sop_payload.meta.unit_type' => ['nullable', 'string', 'in:DZ,GR,HDT,LDT,SDT,ADT', Rule::requiredIf(fn () => in_array($this->input('sop_payload.meta.unit_family'), ['track', 'dumptruck', 'semidump']))],
            'sop_payload.*.groups.*.items.*.status' => ['nullable', 'integer', 'between:1,4'],
            'sop_payload.*.groups.*.items.*.trainee_feedback' => ['nullable', 'string'],
            'sop_payload.*.compliance.*.status' => ['nullable', 'integer', 'between:1,4'],
            'sop_payload.*.compliance.*.trainee_feedback' => ['nullable', 'string'],
            'sop_payload.*.behavior.*.status' => ['nullable', 'integer', 'between:1,4'],
            'sop_payload.*.behavior.*.trainee_feedback' => ['nullable', 'string'],
            'action_type' => ['required', 'in:draft,submit'],
        ];
    }

    public function messages(): array
    {
        $scaleMessage = 'Nilai item evaluasi harus berupa angka 1 (Belum), 2 (Cukup), 3 (Mampu), atau 4 (Mahir).';

        return [
            'sop_payload.meta.unit_type.required' => 'Pilih tipe unit (DZ/GR, HDT/LDT, atau SDT/ADT) sesuai kategori alat.',
            'sop_payload.meta.unit_type.in' => 'Tipe unit yang dipilih tidak valid.',
            'sop_payload.*.groups.*.items.*.status.integer' => $scaleMessage,
            'sop_payload.*.groups.*.items.*.status.between' => $scaleMessage,
            'sop_payload.*.compliance.*.status.integer' => $scaleMessage,
            'sop_payload.*.compliance.*.status.between' => $scaleMessage,
            'sop_payload.*.behavior.*.status.integer' => $scaleMessage,
            'sop_payload.*.behavior.*.status.between' => $scaleMessage,
        ];
    }

    public function withValidator($validator): void
    {
        if ($this->action_type !== 'submit') {
            return;
        }

        $ratings = $this->input('trainer_ratings', []);
        if (empty($ratings)) {
            $validator->errors()->add('trainer_ratings', 'Penilaian trainer wajib diisi sebelum submit.');
        } else {
            $hasRating = false;
            foreach ($ratings as $rating) {
                if (!empty($rating['rating']) && (int) $rating['rating'] > 0) {
                    $hasRating = true;
                    break;
                }
            }
            if (!$hasRating) {
                $validator->errors()->add('trainer_ratings', 'Penilaian trainer wajib diisi sebelum submit.');
            }
        }

        $validator->after(function ($validator) {
            if ($this->action_type === 'submit') {
                $hasInstruktur = !empty($this->input('trainer_id'));
                $hasPengawas = !empty($this->input('selected_pengawas_ids'));
                $hasOperator = !empty($this->input('selected_operator_pendamping_ids'));

                if (!$hasInstruktur && !$hasPengawas && !$hasOperator) {
                    $validator->errors()->add('personnel', 'Pilih minimal salah satu: Instruktur, Pengawas, atau Operator Pendamping.');
                }
            }

            $family = data_get($this->input('sop_payload'), 'meta.unit_family');
            $checklist = data_get($this->input('sop_payload'), $family);

            if (!in_array($family, ['track', 'excavator', 'dumptruck', 'semidump', 'wheelloader'], true) || !is_array($checklist)) {
                $validator->errors()->add('sop_payload', 'Checklist SOP untuk tipe alat yang dipilih wajib diisi.');
                return;
            }

            $blankItems = [];
            foreach (data_get($checklist, 'groups', []) as $group) {
                foreach (data_get($group, 'items', []) as $item) {
                    $note = trim((string)($item['trainee_feedback'] ?? $item['note'] ?? ''));
                    if (!CompetencyScale::isFilled($item['status'] ?? null) && $note === '') {
                        $blankItems[] = $item['code'] . ' - ' . $item['label'];
                    }
                }
            }
            foreach (data_get($checklist, 'compliance', []) as $item) {
                $note = trim((string)($item['trainee_feedback'] ?? $item['note'] ?? ''));
                if (!CompetencyScale::isFilled($item['status'] ?? null) && $note === '') {
                    $blankItems[] = $item['code'] . ' - ' . $item['label'];
                }
            }
            foreach (data_get($checklist, 'behavior', []) as $item) {
                $note = trim((string)($item['trainee_feedback'] ?? $item['note'] ?? ''));
                if (!CompetencyScale::isFilled($item['status'] ?? null) && $note === '') {
                    $blankItems[] = $item['code'] . ' - ' . $item['label'];
                }
            }

            if (!empty($blankItems)) {
                $validator->errors()->add('sop_payload', 'Item evaluasi berikut belum diisi: ' . implode(', ', $blankItems) . '. Pilih nilai 1-4 atau isi trainee feedback.');
            }
        });
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
            'selected_pengawas_ids' => [$isDraft ? 'nullable' : 'required', 'array', 'min:1'],
            'selected_pengawas_ids.*' => ['exists:users,id'],
            'selected_operator_pendamping_ids' => [$isDraft ? 'nullable' : 'required', 'array', 'min:1'],
            'selected_operator_pendamping_ids.*' => ['exists:users,id'],
            'location' => [$isDraft ? 'nullable' : 'required', 'string', 'max:255'],
            'hm_start' => [$isDraft ? 'nullable' : 'required', 'numeric', 'min:0'],
            'hm_end' => [$isDraft ? 'nullable' : 'required', 'numeric', 'gte:hm_start'],
            'daily_activity' => ['nullable', 'string'],
            'daily_activity_backup' => ['nullable', 'string'],
            'trainer_ratings' => ['nullable', 'array'],
            'trainer_ratings.*.user_id' => ['required_with:trainer_ratings', 'exists:users,id'],
            'trainer_ratings.*.rating' => ['required_with:trainer_ratings', 'integer', 'min:1', 'max:5'],
            'sop_payload' => [$isDraft ? 'nullable' : 'required', 'array'],
            'action_type' => ['required', 'in:draft,submit'],
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
        }

        $validator->after(function ($validator) {
            $family = data_get($this->input('sop_payload'), 'meta.unit_family');
            $checklist = data_get($this->input('sop_payload'), $family);

            if (!in_array($family, ['track', 'excavator', 'dumptruck', 'semidump', 'wheelloader'], true) || !is_array($checklist)) {
                $validator->errors()->add('sop_payload', 'Checklist SOP untuk tipe alat yang dipilih wajib diisi.');
                return;
            }

            $blankItems = [];
            foreach (data_get($checklist, 'groups', []) as $group) {
                foreach (data_get($group, 'items', []) as $item) {
                    $status = $item['status'] ?? null;
                    $note = trim((string)($item['note'] ?? ''));
                    if (!in_array($status, ['K', 'BK'], true) && $note === '') {
                        $blankItems[] = $item['code'] . ' - ' . $item['label'];
                    }
                }
            }
            foreach (data_get($checklist, 'compliance', []) as $item) {
                $status = $item['status'] ?? null;
                $note = trim((string)($item['note'] ?? ''));
                if (!in_array($status, ['K', 'BK'], true) && $note === '') {
                    $blankItems[] = $item['code'] . ' - ' . $item['label'];
                }
            }
            foreach (data_get($checklist, 'behavior', []) as $item) {
                $status = $item['status'] ?? null;
                $note = trim((string)($item['note'] ?? ''));
                if (!in_array($status, ['K', 'BK'], true) && $note === '') {
                    $blankItems[] = $item['code'] . ' - ' . $item['label'];
                }
            }

            if (!empty($blankItems)) {
                $validator->errors()->add('sop_payload', 'Item evaluasi berikut belum diisi: ' . implode(', ', $blankItems) . '. Pilih K/BK atau isi catatan penguji.');
            }
        });
    }
}

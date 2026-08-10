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
            'department_id' => [$isDraft ? 'nullable' : 'required', 'exists:departments,id'],
            'equipment_category_id' => [$isDraft ? 'nullable' : 'required', 'exists:equipment_categories,id'],
            'equipment_id' => ['nullable', 'exists:equipments,id'],
            'equipment_number' => [$isDraft ? 'nullable' : 'required', 'string', 'max:100'],
            'trainer_id' => [$isDraft ? 'nullable' : 'required', 'exists:users,id'],
            'supervisor_id' => ['nullable', 'exists:users,id'],
            'location' => [$isDraft ? 'nullable' : 'required', 'string', 'max:255'],
            'start_time' => [$isDraft ? 'nullable' : 'required'],
            'finish_time' => [$isDraft ? 'nullable' : 'required'],
            'hm_start' => [$isDraft ? 'nullable' : 'required', 'numeric', 'min:0'],
            'hm_end' => [$isDraft ? 'nullable' : 'required', 'numeric', 'gte:hm_start'],
            'daily_activity' => [$isDraft ? 'nullable' : 'required', 'string', 'min:10'],
            'sop_payload' => [$isDraft ? 'nullable' : 'required', 'array'],
            'action_type' => ['required', 'in:draft,submit'],
            'evidences' => ['nullable', 'array'],
            'evidences.*' => ['file', 'mimes:jpg,jpeg,png,mp4,pdf,doc,docx', 'max:20480'],
        ];
    }

    public function withValidator($validator): void
    {
        if ($this->action_type !== 'submit') {
            return;
        }

        $validator->after(function ($validator) {
            $family = data_get($this->input('sop_payload'), 'meta.unit_family');
            $checklist = data_get($this->input('sop_payload'), $family);

            if (!in_array($family, ['track', 'excavator'], true) || !is_array($checklist)) {
                $validator->errors()->add('sop_payload', 'Checklist SOP untuk tipe alat yang dipilih wajib diisi.');
                return;
            }

            $statuses = [];
            foreach (data_get($checklist, 'groups', []) as $group) {
                foreach (data_get($group, 'items', []) as $item) $statuses[] = $item['status'] ?? null;
            }
            foreach (data_get($checklist, 'behavior', []) as $item) $statuses[] = $item['status'] ?? null;

            if (!$statuses || collect($statuses)->contains(fn ($status) => !in_array($status, ['K', 'BK'], true))) {
                $validator->errors()->add('sop_payload', 'Semua poin checklist SOP wajib dipilih K atau BK.');
            }
        });
    }
}

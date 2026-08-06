<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLogbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $equipmentCategoryId = $this->input('equipment_category_id');

        return [
            'date' => ['required', 'date'],
            'shift' => ['required', 'in:day,night'],
            'department_id' => ['required', 'exists:departments,id'],
            'equipment_category_id' => ['required', 'exists:equipment_categories,id'],
            'equipment_id' => [
                'required',
                Rule::exists('equipments', 'id')->where(fn ($query) => $query->where('equipment_category_id', $equipmentCategoryId)),
            ],
            'trainer_id' => ['required', 'exists:users,id'],
            'supervisor_id' => ['nullable', 'exists:users,id'],
            'location' => ['required', 'string', 'max:255'],
            'start_time' => ['required'],
            'finish_time' => ['required'],
            'hm_start' => ['required', 'numeric', 'min:0'],
            'hm_end' => ['required', 'numeric', 'gte:hm_start'],
            'daily_activity' => ['required', 'string', 'min:10'],
            'sop_payload' => ['nullable', 'array'],
            'action_type' => ['required', 'in:draft,submit'],
            'evidences' => ['nullable', 'array'],
            'evidences.*' => ['file', 'mimes:jpg,jpeg,png,mp4,pdf,doc,docx', 'max:20480'],
        ];
    }
}

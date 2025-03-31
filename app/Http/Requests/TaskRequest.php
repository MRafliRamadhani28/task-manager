<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'task_description'          => 'required|string',
            'hourly_rate'               => 'required|numeric|min:0',
            'additional_charges'        => 'nullable|numeric|min:0',
            'employees'                 => 'required|array',
            'employees.*.id'            => 'required|exists:employees,id',
            'employees.*.hours_spent'   => 'required|numeric|min:0.5',
        ];
    }
}

<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255|unique:tasks',
            'description' => 'nullable|string',
            'priority' => 'required|string|in:high,medium,low',
            'due_date' => 'required|date_format:d-m-Y H:i',
            'status' => 'nullable|string|in:pending,in_progress,completed,overdue',
            'assigned_to' => 'required|exists:users,id',
        ];
    }
}

<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255|unique:tasks,title,' . $this->task,
            'description' => 'nullable|string',
            'priority' => 'sometimes|string|in:high,medium,low',
            'due_date' => 'sometimes|date_format:d-m-Y H:i',
            'status' => 'sometimes|string|in:pending,in_progress,completed,overdue',
            'assigned_to' => 'nullable|exists:users,id',
        ];
    }
}

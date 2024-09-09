<?php

namespace App\Http\Requests\TaskRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest   extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'required|string|in:pending,in_progress,completed,overdu',
        ];
    }
}

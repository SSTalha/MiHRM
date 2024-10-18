<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublishedStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'is_published' => 'required|boolean', // Validate that the field is a boolean (true or false)
        ];
    }

    public function messages()
    {
        return [
            'is_published.required' => 'The published status is required.',
            'is_published.boolean' => 'The published status must be true or false.',
        ];
    }
}

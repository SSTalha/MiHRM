<?php

namespace App\Http\Requests\Perks;

use Illuminate\Foundation\Http\FormRequest;

class CreatePerkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'allowance' => 'required|integer|min:1', 
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title of the perk is required.',
            'allowance.required' => 'The allowance is required.',
            'allowance.integer' => 'The allowance must be a valid number.',
            'allowance.min' => 'The allowance must be at least 1.',
        ];
    }
}

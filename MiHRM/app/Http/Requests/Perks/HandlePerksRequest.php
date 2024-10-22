<?php

namespace App\Http\Requests\Perks;

use Illuminate\Foundation\Http\FormRequest;

class HandlePerksRequest extends FormRequest
{
    public function authorize()
    {
        
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'required|in:approved,rejected',
        ];
    }

    public function messages()
    {
        return [
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be either approved or rejected.',
        ];
    }
}

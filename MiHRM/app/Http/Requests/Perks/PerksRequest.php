<?php

namespace App\Http\Requests\Perks;

use Illuminate\Foundation\Http\FormRequest;

class PerksRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'requested_perks' => 'required|array|min:1',
            'requested_perks.*' => 'integer|exists:perks,id', 
        ];
    }

    public function messages()
    {
        return [
            'requested_perks.required' => 'You must request at least one perk.',
            'requested_perks.*.integer' => 'Each requested perk must be a valid ID.',
            'requested_perks.*.exists' => 'Some of the requested perks do not exist.',
        ];
    }
}

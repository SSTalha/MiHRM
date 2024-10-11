<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
class UpdateEmployeeRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'department_id.required' => 'The department field is required.',
            'department_id.exists' => 'The selected department is invalid.',
            'position.required' => 'The position field is required.',
            'position.string' => 'The position must be a valid string.',
            'position.max' => 'The position cannot be longer than 255 characters.',
        ];
    }
}

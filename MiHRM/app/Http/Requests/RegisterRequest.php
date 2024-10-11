<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends BaseRequest
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
            // User validation rules
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|exists:roles,name', 

            // Employee validation rules
            'position' => 'required|string|max:255',
            'department_id' => 'required|integer|exists:departments,id', 

            'pay' => 'required|integer|min:1', 

            'date_of_joining' => 'sometimes|date|before_or_equal:today', 
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
            // User validation messages
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'This email is already registered.',
            'role.required' => 'The role field is required.',
            'role.exists' => 'The selected role does not exist.',

            // Employee validation messages
            'position.required' => 'The position field is required.',
            'department_id.required' => 'The department field is required.',
            'department_id.exists' => 'The selected department does not exist.',

            // Salary validation messages
            'pay.required' => 'The pay field is required.',
            'pay.integer' => 'The pay must be an integer value.',
            'pay.min' => 'The pay must be a positive number.',

            // Date of Joining validation messages
            'date_of_joining.date' => 'The date of joining must be a valid date.',
            'date_of_joining.before_or_equal' => 'The date of joining cannot be in the future.',
        ];
    }
}
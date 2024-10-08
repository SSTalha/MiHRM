<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AssignProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Only HR can assign projects
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
            'project_id' => 'required|exists:projects,id',
            'employee_id' => 'required|exists:employees,id',
            'status' => 'in:in_progress,pending,completed',
        ];
    }

    /**
     * Custom error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'project_id.required' => 'The project ID is required.',
            'project_id.exists' => 'The selected project does not exist.',
            'employee_id.required' => 'The employee ID is required.',
            'employee_id.exists' => 'The selected employee does not exist.',
           
        ];
    }
}

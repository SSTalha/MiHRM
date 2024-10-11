<?php

namespace App\Http\Requests\Employee;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class WorkingHourRequest extends BaseRequest
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
            'employee_id' => 'required|integer|exists:employees,id',
            'date' => 'required|date',  // Start date
            'frequency' => 'required|in:weekly,monthly',  // 'weekly' or 'monthly'
        ];
    }

    /**
     * Custom error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'employee_id.required' => 'The employee ID is required.',
            'employee_id.exists' => 'The employee does not exist.',
            'date.required' => 'The start date is required.',
            'frequency.required' => 'The frequency (weekly or monthly) is required.',
            'frequency.in' => 'Frequency must be either weekly or monthly.',
        ];
    }
}

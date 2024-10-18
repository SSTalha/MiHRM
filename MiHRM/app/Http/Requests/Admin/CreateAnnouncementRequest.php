<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class CreateAnnouncementRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'published_at' => 'nullable|date|after:now',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title is required.',
            'text.required' => 'The text of the announcement is required.',
            'published_at.after' => 'The publication date must be in the future.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHrRequestRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'type' => 'required|string|in:resignation,complaint,other',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
        ];
    }
}

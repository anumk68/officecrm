<?php
// app/Http/Requests/StorePolicyRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePolicyRequest extends FormRequest
{
    public function authorize()
    {
      
        return auth()->check() && in_array(auth()->user()->role, ['hr', 'manager']);
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|max:5120|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,txt',
        ];
    }
}

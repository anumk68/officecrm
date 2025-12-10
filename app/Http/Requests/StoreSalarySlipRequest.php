<?php
 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalarySlipRequest extends FormRequest
{
    public function authorize()
    {
        // only HR/manager allowed to generate slips
        return auth()->check() && in_array(auth()->user()->role, ['hr', 'manager']);
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'month'   => 'required|string|max:20',
            'basic'   => 'required|numeric|min:0',
            'hra'     => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'metadata' => 'nullable',
            'metadata.*.label' => 'nullable|string|max:100',
            'metadata.*.amount' => 'nullable|numeric',
        ];
    }
}


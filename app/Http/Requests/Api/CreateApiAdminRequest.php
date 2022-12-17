<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class CreateApiAdminRequest extends ApiFormRequest
{
    public function authorize()
    { 
        return $this;
    }

    public function rules()
    {
        return [
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'company_id' => ['sometimes', 'required', 'integer', 'exists:companies,id'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => [
                'required',
                Rule::in(['api_admin', 'company_admin', 'recruiter'])
            ],
            'password' => ['required', 'max:255'],
            'secret_key' => [
                'required',
                Rule::in(['123456'])
            ]
        ];
    }

    public function messages()
    {
        return [
            'role.in' => 'Invalid role, must be any of the accepted ones => [\'api_admin\', \'company_admin\', \'recruiter\'] ',
            'secret_key.in' => 'Invalid secret key'
        ];
    }
}
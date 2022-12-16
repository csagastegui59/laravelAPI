<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends ApiFormRequest
{
    public function authorize()
    { 
        $user = $this->user();

        return $user != null && $user->role != 'candidate' ? true : false;
    }

    public function rules()
    {
        $user = $this->user();
        
        switch ($user) {
            case ($user->role == 'api_admin'):
                return [
                    'first_name' => ['sometimes', 'required', 'max:255'],
                    'last_name' => ['sometimes', 'required', 'max:255'],
                    'company_id' => ['sometimes', 'required', 'integer'],
                    'email' => ['sometimes', 'required', 'email', 'unique:users,email'],
                    'role' => [
                        'sometimes',
                        'required',
                        Rule::in(['api_admin', 'company_admin', 'recruiter'])
                    ],
                    'password' => ['sometimes', 'required', 'max:255']
                ];
            case ($user->role == 'company_admin'):
                return [
                    'first_name' => ['sometimes', 'required', 'max:255'],
                    'last_name' => ['sometimes', 'required', 'max:255'],
                    'company_id' => ['required', 'integer', 'size:'.$user->company_id],
                    'email' => ['sometimes', 'required', 'email', 'unique:users,email'],
                    'role' => [
                        'sometimes',
                        'required',
                        Rule::in(['recruiter'])
                    ],
                    'password' => ['sometimes', 'required', 'max:255']
                ];
            case ($user->role == 'recruiter'):
                return [
                    'first_name' => ['sometimes', 'required', 'max:255'],
                    'last_name' => ['sometimes', 'required', 'max:255'],
                    'company_id' => ['required', 'integer', 'size:'.$user->company_id],
                    'email' => ['sometimes', 'required', 'email', 'unique:users,email'],
                    'role' => [
                        'sometimes', 
                        'required',
                        Rule::in(['recruiter'])
                    ],
                    'password' => ['sometimes', 'required', 'max:255']
                ];
        }
    }

    public function messages()
    {
        $user = $this->user();

        switch ($user) {
            case ($user->role == 'api_admin'):
                return [
                    'role.in' => 'Invalid role, must be any of the accepted ones => [\'api_admin\', \'company_admin\', \'recruiter\'] '
                ];
            case ($user->role == 'company_admin'):
                return [
                    'role.in' => 'Invalid role, must be any of the accepted ones => [\'recruiter\'] ',
                    'company_id.size' => 'You can only update users using your company id, your company id is: ' .$user->company_id
            ];
            case ($user->role == 'recruiter'):
                return [
                    'role.in' => 'Invalid role, must be any of the accepted ones => [\'recruiter\']',
                    'company_id.size' => 'You can only update users using your company id, your company id is: ' .$user->company_id
            ];
        }
    }
}
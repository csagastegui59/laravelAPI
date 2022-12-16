<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends ApiFormRequest
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
                    'first_name' => ['required', 'max:255'],
                    'last_name' => ['required', 'max:255'],
                    'company_id' => ['integer'],
                    'email' => ['required', 'email', 'unique:users,email'],
                    'role' => [
                        'required',
                        Rule::in(['api_admin', 'company_admin', 'recruiter'])
                    ],
                    'password' => ['required', 'max:255']
                ];
            case ($user->role == 'company_admin'):
                return [
                    'first_name' => ['required', 'max:255'],
                    'last_name' => ['required', 'max:255'],
                    'company_id' => ['integer', 'size:'.$user->company_id],
                    'email' => ['required', 'email', 'unique:users,email'],
                    'role' => [
                        'required',
                        Rule::in(['recruiter'])
                    ],
                    'password' => ['required', 'max:255']
                ];
            case ($user->role == 'recruiter'):
                return [
                    'first_name' => ['required', 'max:255'],
                    'last_name' => ['required', 'max:255'],
                    'company_id' => ['integer', 'size:'.$user->company_id],
                    'email' => ['required', 'email', 'unique:users,email'],
                    'role' => [
                        'required',
                        Rule::in(['recruiter'])
                    ],
                    'password' => ['required', 'max:255']
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
                    'company_id.size' => 'You can only create users using you company id, your company id is: ' .$user->company_id
            ];
            case ($user->role == 'recruiter'):
                return [
                    'role.in' => 'Invalid role, must be any of the accepted ones => [\'recruiter\']',
                    'company_id.size' => 'You can only create users using you company id, your company id is: ' .$user->company_id
            ];
        }
    }
}
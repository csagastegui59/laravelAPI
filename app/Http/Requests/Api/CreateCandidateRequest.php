<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class CreateCandidateRequest extends ApiFormRequest
{
    public function authorize()
    { 
        $user = $this->user();

        return $user != null && isset($user->role) ? true : false;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email', 'unique:users,email', 'unique:candidates,email'],
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'password' => ['required', 'max:255'],
            'application_status' => [
                'required',
                Rule::in(['human_resources', 'engineering', 'accepted'])
            ]
        ];
    }

    public function messages()
    {
        return [
            'application_status.in' => 'Invalid application status, must be any of the accepted ones => [\'human_resources\', \'engineering\', \'accepted\']',
            'email.unique' => 'Email has been already taken'
        ];
    }
}
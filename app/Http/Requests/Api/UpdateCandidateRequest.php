<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateCandidateRequest extends ApiFormRequest
{
    public function authorize()
    { 
        $user = $this->user();

        return $user != null && isset($user->role) ? true : false;
    }

    public function rules()
    {
        return [
            'first_name' => ['sometimes', 'required', 'max:255'],
            'last_name' => ['sometimes', 'required', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'unique:users,email', 'unique:candidates,email'],
            'password' => ['sometimes', 'required', 'max:255'],
            'application_status' => [
                'sometimes',
                'required',
                Rule::in(['human_resources', 'engineering', 'accepted'])
            ]
        ];
    }

    public function messages()
    {
        return [
            'application_status.in' => 'Invalid application_status, must be any of the accepted ones => [\'human_resources\', \'engineering\', \'accepted\'] '
        ];
    }
}
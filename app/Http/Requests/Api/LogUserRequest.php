<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

class LogUserRequest extends ApiFormRequest
{
    public function authorize()
    {
        $user = $this->user();

        if ($user == null) {

            return true;
        } else {

            return false;
        }
    }

    public function rules()
    {
        return [
            'email' => ['required', 'exists:users'],
            'password' => ['required', 'max:255']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email can\'t be empty',
            'email.exists' => 'Credentials does not match our records',
            'password.required' => 'Password can\'t be empty',
        ];
    }
}
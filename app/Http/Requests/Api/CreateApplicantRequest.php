<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

class CreateApplicantRequest extends ApiFormRequest
{
    public function authorize()
    { 
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'max:255', 'unique:applicants,email', 'email'],
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255']
        ];
    }
}
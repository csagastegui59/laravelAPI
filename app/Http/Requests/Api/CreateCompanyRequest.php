<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

class CreateCompanyRequest extends ApiFormRequest
{
    public function authorize()
    { 
        $user = $this->user();

        return $user != null && $user->role == 'api_admin' ? true : false;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'max:255', 'unique:companies,name'],
        ];
    }
}
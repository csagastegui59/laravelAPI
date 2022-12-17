<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

class CreateJobOpeningRequest extends ApiFormRequest
{
    public function authorize()
    { 
        $user = $this->user();

        return $user != null ? true : false;
    }

    public function rules()
    {
        return [
            'title' => ['required', 'max:255'],
            'description' => ['required', 'max:2000'],
            'is_published' => ['required', 'boolean']
        ];
    }
}
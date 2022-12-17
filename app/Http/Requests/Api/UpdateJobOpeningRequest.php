<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

class UpdateJobOpeningRequest extends ApiFormRequest
{
    public function authorize()
    {
        $user = $this->user();

        return $user != null && $user->role ? true : false;
    }

    public function rules()
    {
        return [
            'title' => ['sometimes', 'required', 'max:255'],
            'description' => ['sometimes', 'required', 'max:2000'],
            'is_published' => ['sometimes', 'required', 'boolean']
        ];
    }
}
<?php

namespace App\Http\Controllers\Api\Interfaces;
use App\Models\Api\Company;
use App\Http\Requests\Api\CreateUserRequest;

interface CreateUserInterface
{
    public function store(Company $company, CreateUserRequest $request);
}
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{ 
    AuthController,
    CompanyController, 
    JobOpeningController,
    ApplicantController,
    CandidateController,
    UserController
};

Route::post('register', [AuthController::class, 'store']);
Route::post('login', [ AuthController::class, 'login']);
Route::resource('companies.jobOpenings.applicants', ApplicantController::class)
    ->only('store');

Route::middleware(['auth:sanctum'])->group(function() {
    Route::resource('companies.jobOpenings.applicants', ApplicantController::class)
        ->only('index', 'show', 'destroy')
        ->middleware('ability:api:admin,company:admin,company:recruiter');

    Route::resource('companies.jobOpenings.candidates', CandidateController::class)
        ->only('index', 'store', 'show', 'update', 'destroy')
        ->middleware('ability:api:admin,company:admin,company:recruiter');

    Route::resource('companies', CompanyController::class)
        ->only('show', 'index')
        ->middleware('ability:company:recruiter,api:admin,company:admin');

    Route::resource('companies', CompanyController::class)
        ->only('store', 'destroy')
        ->middleware('ability:api:admin');

    Route::resource('companies', CompanyController::class)
        ->only('update')
        ->middleware('ability:api:admin,company:admin');

    Route::resource('companies.jobOpenings', JobOpeningController::class)
        ->only('index', 'store', 'show', 'update', 'destroy')
        ->middleware('ability:api:admin,company:admin,company:recruiter');

    Route::resource('companies.users', UserController::class)
        ->only('index', 'store', 'show', 'update', 'destroy')
        ->middleware('ability:api:admin,company:admin,company:recruiter');
    
    Route::get('companies/{company}/jobOpenings/{jobOpening}/reports',[JobOpeningController::class, 'reports'])
        ->middleware('ability:api:admin,company:admin,company:recruiter');

    Route::post('logout', [AuthController::class, 'logout']);
});
<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\{ User, Candidate, Company };
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{ Auth, Hash };
use App\Http\Requests\Api\{ CreateUserRequest, LogUserRequest, CreateApiAdminRequest };

class AuthController extends Controller
{
  public function store(CreateApiAdminRequest $request)
  {
    try {
        $validated = $request->validated();
        $company = '';

        !isset($validated['company_id']) 
        ?
        $company = Company::first()
        :
        $company = Company::where('id', $validated['company_id'])->first();

        
        $user = User::create([
            'company_id' => $company->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'user' => $user,
        ], 201);

    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ], 500);
    }
  }

  public function login(LogUserRequest $request)
  {
    
    $validated = $request->validated();

    $user = User::where('email', $request->email)->first();

    if(Hash::check($request->password, $user->password)) {
        $token = '';
        
        switch ($user->role){
            case('api_admin'):
                $token = $user->createToken("api-admin-token", ['api:admin'])->plainTextToken;
                break;
            case('company_admin'):
                $token = $user->createToken("company-admin-token", ['company:admin'])->plainTextToken;
                break;
            case('recruiter'):
                $token = $user->createToken("company-recruiter-token", ['company:recruiter'])->plainTextToken;
                break;
        }

        return response()->json([
          'status' => true,
          'message' => 'User Logged In Successfully',
          'user' => $user,
          'token' => $token
        ], 200);

    } else {

        return response()->json([
            'status' => false,
            'message' => 'Credentials doesn\'t match our records',
        ], 401);
    }
  }

  public function logout()
  {
    $user = Auth::user();
    $user->tokens->each->delete();

    return response()->json([
      'status' => true,
      'message' => 'logged out',
    ], 200);
  }
}
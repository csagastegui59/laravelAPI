<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\{ User, Candidate };
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{ Auth, Hash };
use App\Http\Requests\Api\{ CreateUserRequest, LogUserRequest, CreateTokenRequest };

class AuthController extends Controller
{
  public function store(CreateUserRequest $request)
  {
    try {
      $validated = $request->validated();

      $user = User::create([
        'company_id' => $validated['company_id'],
        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],
        'email' => $validated['email'],
        'role' => $validated['role'],
        'password' => Hash::make($validated['password'])
      ]);

      $token = $this->createToken(
          $user
      );

      return response()->json([
        'status' => true,
        'message' => 'User Created Successfully',
        'user' => $user,
        'token' => $token
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
        ], 201);

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
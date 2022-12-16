<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\{ User, Company };
use Illuminate\Support\Facades\{ Auth, Hash };
use App\Http\Requests\Api\{ CreateUserRequest, UpdateUserRequest };
use App\Http\Controllers\Api\Interfaces\CreateUserInterface;

class UserController extends Controller implements CreateUserInterface
{
    public function index(Company $company)
    {
        try {
            $users = User::where('company_id', $company->id)
                ->paginate(10);
    
            return response()->json([
                'status' => true,
                'message' => $company->name . ' users',
                'pagination' => [
                    'total_items' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'has_more_pages' => $users->hasMorePages(),
                    'total_pages' => $users->lastPage(),
                    'previous' => $users->previousPageUrl(),
                    'next' => $users->nextPageUrl(),
                    'has_pages' => $users->hasPages(),
                ],
                'users' => $users->items()
            ], 200);
    
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
    }

    public function store(Company $company,CreateUserRequest $request)
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

    public function show(Company $company, $id)
    {
        try {
            $user = User::where('id', $id)
                ->where('company_id', $company->id)
                ->first();

            return $user != null 
            ?
                response()->json([
                    'status' => true,
                    'message' => 'company user',
                    'user' => $user
                ], 200)
            :
                response()->json([
                    'status' => true,
                    'message' => 'No user found with given id'
                ], 200);

    
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Company $company, UpdateUserRequest $request, $id)
    {   
        try {
            $user = User::where('id', $id)->first();
            $validated = $request->validated();

            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }

            if ($user != null) {
                $user->update($validated);
    
                return response()->json([
                    'status' => true,
                    'message' => 'User updated',
                    'user' => $user
                ], 201);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'No user found with given id'
                ], 201);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        
    }

    public function destroy(Company $company, $id)
    {   
        try {
            $user = User::where('id', $id)->first();

            if ($user != null){
                $user->delete();
                return response()->json([
                    'status'=> true,
                    'message' => 'user deleted', 
                ], 200);
            } else {
                return response()->json([
                    'status'=> true,
                    'message' => 'No user found with given id', 
                ], 200);
            }
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}

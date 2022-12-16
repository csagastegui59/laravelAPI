<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\{ CreateCompanyRequest, UpdateCompanyRequest };

class CompanyController extends Controller
{
    public function store(CreateCompanyRequest $request)
    { 
        try {
            $validated = $request->validated();
            $company = Company::create($validated);
            
            return response()->json([
                'status' => true,
                'message' => 'Company created successfuly',
                'company' => $company
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $companies = Company::with('users', 'jobOpenings')
            ->paginate(10);

            return response()->json([
                'status' => true,
                'message' => 'All Companies',
                'pagination' => [
                    'total_items' => $companies->total(),
                    'per_page' => $companies->perPage(),
                    'current_page' => $companies->currentPage(),
                    'has_more_pages' => $companies->hasMorePages(),
                    'total_pages' => $companies->lastPage(),
                    'previous' => $companies->previousPageUrl(),
                    'next' => $companies->nextPageUrl(),
                    'has_pages' => $companies->hasPages(),
                ],
                'companies' => $companies->items()
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {   
        try {
            $company = Company::where('id', $id)->first();

            if ($company != null) {
                return response()->json([
                    'status' => true,
                    'message' => $company->name .' Company',
                    'company' => $company
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'No company found with given id'
                ], 200);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(UpdateCompanyRequest $request, $id)
    {   
        try {
            $company = Company::where('id', $id)->first();
            $validated = $request->validated();

            if ($company != null) {
                $company->update($validated);
    
                return response()->json([
                    'status' => true,
                    'message' => 'Company updated',
                    'company' => $company
                ], 201);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'No company found with given id'
                ], 201);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $company = Company::where('id', $id)->first();

            if ($company != null){
                $company->delete();
                return response()->json([
                    'status'=> true,
                    'message' => 'company deleted', 
                ], 200);
            } else {
                return response()->json([
                    'status'=> true,
                    'message' => 'No company found with given id', 
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Api\{ Company, JobOpening, Candidate };
use App\Http\Requests\Api\{ CreateCandidateRequest, UpdateCandidateRequest };

class CandidateController extends Controller
{
    public function index(Company $company, JobOpening $jobOpening)
    {
        try {
            $candidates = Candidate::where('job_opening_id', $jobOpening->id)
                ->paginate(10);
    
            return response()->json([
                'status' => true,
                'message' => $jobOpening->title . ' candidates',
                'pagination' => [
                    'total_items' => $candidates->total(),
                    'per_page' => $candidates->perPage(),
                    'current_page' => $candidates->currentPage(),
                    'has_more_pages' => $candidates->hasMorePages(),
                    'total_pages' => $candidates->lastPage(),
                    'previous' => $candidates->previousPageUrl(),
                    'next' => $candidates->nextPageUrl(),
                    'has_pages' => $candidates->hasPages(),
                ],
                'candidates' => $candidates->items()
            ], 200);
    
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
    }

    public function store(Company $company, JobOpening $jobOpening, CreateCandidateRequest $request)
    {
        try {
            $validated = $request->validated();

            $candidate = Candidate::create([
                'job_opening_id' => $jobOpening->id,
                'email' => $validated['email'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'application_status' => $validated['application_status'],
                'password' => Hash::make($validated['password'])
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Candidate successfuly created',
                'candidate' => $candidate
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show(Company $company, JobOpening $jobOpening, $id)
    {
        try {
            $candidate = Candidate::where(
                'id', $id
            )->first();

            return $candidate != null 
            ?
                response()->json([
                    'status' => true,
                    'message' => 'Candidate',
                    'candidate' => $candidate
                ], 200)
            :
                response()->json([
                    'status' => true,
                    'message' => 'No candidate found with given id'
                ], 200);

    
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Company $company, JobOpening $jobOpening, UpdateCandidateRequest $request, $id)
    {
        try {
            $candidate = Candidate::where('id', $id)->first();
            $validated = $request->validated();

            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }

            if ($candidate != null) {
                $candidate->update($validated);
    
                return response()->json([
                    'status' => true,
                    'message' => 'Candidate updated',
                    'candidate' => $candidate
                ], 201);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'No candidate found with given id'
                ], 201);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Company $company, JobOpening $jobOpening, $id)
    {
        try {
            $candidate = Candidate::where('id', $id)->first();

            if ($candidate != null){
                $candidate->delete();
                return response()->json([
                    'status'=> true,
                    'message' => 'candidate deleted', 
                ], 200);
            } else {
                return response()->json([
                    'status'=> true,
                    'message' => 'No candidate found with given id', 
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

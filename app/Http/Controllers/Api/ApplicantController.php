<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\{ Company, JobOpening, Applicant };
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateApplicantRequest;

class ApplicantController extends Controller
{
    public function index(Company $company, JobOpening $jobOpening)
    {
        try {
            $applicants = Applicant::where('job_opening_id', $jobOpening->id)
                ->paginate(10);
    
            return response()->json([
                'status' => true,
                'message' => $jobOpening->title . ' applicants',
                'pagination' => [
                    'total_items' => $applicants->total(),
                    'per_page' => $applicants->perPage(),
                    'current_page' => $applicants->currentPage(),
                    'has_more_pages' => $applicants->hasMorePages(),
                    'total_pages' => $applicants->lastPage(),
                    'previous' => $applicants->previousPageUrl(),
                    'next' => $applicants->nextPageUrl(),
                    'has_pages' => $applicants->hasPages(),
                ],
                'applicants' => $applicants->items()
            ], 200);
    
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
    }

    public function store(Company $company, JobOpening $jobOpening, CreateApplicantRequest $request)
    {
        try {
            $validated = $request->validated();

            $applicant = Applicant::create([
                'job_opening_id' => $jobOpening->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email']
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Applicant successfuly created',
                'applicant' => $applicant
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
            $applicant = Applicant::where(
                'id', $id
            )->first();

            return $applicant != null 
            ?
                response()->json([
                    'status' => true,
                    'message' => 'Applicant',
                    'applicant' => $applicant
                ], 200)
            :
                response()->json([
                    'status' => true,
                    'message' => 'No applicant found with given id'
                ], 200);

    
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
            $applicant = Applicant::where('id', $id)->first();

            if ($applicant != null){
                $applicant->delete();
                return response()->json([
                    'status'=> true,
                    'message' => 'applicant deleted', 
                ], 200);
            } else {
                return response()->json([
                    'status'=> true,
                    'message' => 'No applicant found with given id', 
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

<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\{ Company, JobOpening, Candidate };
use App\Http\Requests\Api\{ CreateJobOpeningRequest, UpdateJobOpeningRequest };
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class JobOpeningController extends Controller
{
    public function index(Company $company)
    {
        try {
            $jobOpenings = JobOpening::where('company_id', $company->id)
                ->paginate(10);

            return response()->json([
                'status' => true,
                'message' => $company->name . ' Job Openings',
                'pagination' => [
                    'total_items' => $jobOpenings->total(),
                    'per_page' => $jobOpenings->perPage(),
                    'current_page' => $jobOpenings->currentPage(),
                    'has_more_pages' => $jobOpenings->hasMorePages(),
                    'total_pages' => $jobOpenings->lastPage(),
                    'previous' => $jobOpenings->previousPageUrl(),
                    'next' => $jobOpenings->nextPageUrl(),
                    'has_pages' => $jobOpenings->hasPages(),
                ],
                'jobOpenings' => $jobOpenings->items()
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Company $company, CreateJobOpeningRequest $request)
    {
        try {
            $validated = $request->validated();

            $jobOpening = JobOpening::create([
                'company_id' => $company->id,
                'title' => $validated['title'],
                'description' => $validated['description'],
                'is_published' => $validated['is_published']
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Job opening successfuly created',
                'jobOpening' => $jobOpening
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
            $jobOpening = JobOpening::where(
                'id', $id
            )->first();

            return $jobOpening != null 
            ?
                response()->json([
                    'status' => true,
                    'message' => 'Job opening',
                    'jobOpening' => $jobOpening
                ], 200)
            :
                response()->json([
                    'status' => true,
                    'message' => 'No job opening found with given id'
                ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Company $company, UpdateJobOpeningRequest $request, $id)
    {
        try {
            $jobOpening = JobOpening::where('id', $id)->first();
            $validated = $request->validated();

            if ($jobOpening != null) {
                $jobOpening->update([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'is_published' => $validated['is_published']
                ]);
    
                return response()->json([
                    'status' => true,
                    'message' => 'Job opening updated',
                    'jobOpening' => $jobOpening
                ], 201);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'No job opening found with given id'
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
            $jobOpening = JobOpening::where('id', $id)->first();

            if ($jobOpening != null){
                $jobOpening->delete();
                return response()->json([
                    'status'=> true,
                    'message' => 'Job opening deleted', 
                ], 200);
            } else {
                return response()->json([
                    'status'=> true,
                    'message' => 'No Job opening found with given id', 
                ], 200);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function reports(Company $company, $id)
    {
        try{
            $candidates = Candidate::where('job_opening_id', $id)
                ->get();

            return response()->json([
                'status'=> true,
                'message' => 'Job opening report',
                'total_candidates' => $candidates->count(),
                'accepted_candidates' => $candidates->where('application_status', 'accepted')->count(),
                'engineering_candidates' => $candidates->where('application_status', 'engineering')->count(),
                'human_resources_candidates' => $candidates->where('application_status', 'human_resources')->count()
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        return $candidates;
    }
}

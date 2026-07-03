<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Models\JobOpening;
use App\Models\JobDepartment;
use App\Models\JobCategory;
use App\Models\JobLocation;
use App\Services\JobApplicationService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class CareersController extends Controller
{
    protected $applicationService;

    public function __construct(JobApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    /**
     * Show the main careers landing page.
     */
    public function index(): View
    {
        $featuredJobs = JobOpening::active()->published()->featured()->ordered()->take(3)->get();
        $latestJobs = JobOpening::active()->published()->ordered()->take(6)->get();
        
        $stats = [
            'total_openings' => JobOpening::active()->published()->count(),
            'departments_count' => JobDepartment::active()->count(),
            'locations_count' => JobLocation::active()->count(),
        ];

        return view('careers.index', compact('featuredJobs', 'latestJobs', 'stats'));
    }

    /**
     * Show the job openings listings page with filters.
     */
    public function jobs(Request $request): View
    {
        $query = JobOpening::with(['department', 'category', 'location'])->active()->published();

        // Keyword search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->input('location_id'));
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->input('employment_type'));
        }

        if ($request->filled('experience_level')) {
            $query->where('experience_level', $request->input('experience_level'));
        }

        if ($request->filled('featured')) {
            $query->featured();
        }

        if ($request->filled('salary_min')) {
            $query->where('salary_to', '>=', $request->input('salary_min'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'salary_high') {
            $query->orderByRaw('CASE WHEN salary_to IS NULL THEN 0 ELSE salary_to END DESC');
        } elseif ($sort === 'vacancies') {
            $query->orderBy('vacancies', 'desc');
        } else {
            // default ordered (featured first, then published date)
            $query->ordered();
        }

        $jobs = $query->paginate(9)->withQueryString();

        // Fetch filter options
        $departments = JobDepartment::active()->ordered()->get();
        $categories = JobCategory::active()->ordered()->get();
        $locations = JobLocation::active()->ordered()->get();
        
        $employmentTypes = JobOpening::active()->published()
            ->select('employment_type')
            ->distinct()
            ->pluck('employment_type')
            ->filter();

        $experienceLevels = JobOpening::active()->published()
            ->select('experience_level')
            ->distinct()
            ->pluck('experience_level')
            ->filter();

        return view('careers.jobs', compact('jobs', 'departments', 'categories', 'locations', 'employmentTypes', 'experienceLevels'));
    }

    /**
     * Show details of a specific job opening.
     */
    public function show(string $slug): View
    {
        $job = JobOpening::with(['department', 'category', 'location', 'skills', 'benefits', 'qualifications'])
            ->active()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedJobs = JobOpening::with(['location', 'department'])
            ->active()
            ->published()
            ->where('category_id', $job->category_id)
            ->where('id', '!=', $job->id)
            ->take(3)
            ->get();

        return view('careers.show', compact('job', 'relatedJobs'));
    }

    /**
     * Process online job application submission.
     */
    public function apply(StoreJobApplicationRequest $request, string $slug): JsonResponse|RedirectResponse
    {
        $job = JobOpening::active()->published()->where('slug', $slug)->firstOrFail();
        
        // Prevent duplicate applications for the same email on the same job
        $exists = \App\Models\JobApplication::where('job_opening_id', $job->id)
            ->where('email', $request->input('email'))
            ->exists();

        if ($exists) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['email' => ['You have already applied for this position.']],
                ], 422);
            }
            return back()->withInput()->withErrors(['email' => 'You have already applied for this position.']);
        }

        $data = $request->validated();
        $data['job_opening_id'] = $job->id;

        $this->applicationService->store($data, $request->file('resume'));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your application has been submitted successfully.',
            ]);
        }

        return redirect()->route('careers.show', $job->slug)
            ->with('success', 'Thank you! Your application has been submitted successfully.');
    }
}

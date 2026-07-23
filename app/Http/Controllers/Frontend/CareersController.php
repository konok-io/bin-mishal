<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CareersController extends Controller
{
    /**
     * Display job listings
     */
    public function index(Request $request)
    {
        $query = Job::published();

        // Filter by department
        if ($request->has('department')) {
            $query->where('department', $request->department);
        }

        // Filter by location
        if ($request->has('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        // Filter by employment type
        if ($request->has('type')) {
            $query->where('employment_type', $request->type);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%")
                    ->orWhere('department', 'LIKE', "%{$search}%");
            });
        }

        $jobs = $query->orderBy('is_featured', 'desc')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $departments = Job::DEPARTMENTS;
        $employmentTypes = Job::EMPLOYMENT_TYPES;

        return view('frontend.careers.index', compact(
            'jobs',
            'departments',
            'employmentTypes'
        ));
    }

    /**
     * Display single job detail
     */
    public function show(string $slug)
    {
        $job = Job::where('slug', $slug)
            ->orWhere('id', $slug)
            ->published()
            ->firstOrFail();

        return view('frontend.careers.show', compact('job'));
    }

    /**
     * Submit job application
     */
    public function apply(Request $request, string $slug)
    {
        $job = Job::where('slug', $slug)
            ->orWhere('id', $slug)
            ->published()
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'phone_country_code' => 'required|string|max:10',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|string|max:2000',
            'consent' => 'required|accepted',
        ], [
            'cv.max' => 'CV file size must be less than 5MB',
            'cv.mimes' => 'CV must be a PDF or Word document',
            'consent.accepted' => 'You must agree to the data processing terms',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Store CV file
        $cvPath = $request->file('cv')->store('careers/cv', 'public');

        // Create application
        $application = JobApplication::create([
            'job_id' => $job->id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'phone_country_code' => $request->phone_country_code,
            'cover_letter' => $request->cover_letter,
            'cv_path' => $cvPath,
            'status' => JobApplication::STATUS_RECEIVED,
            'applied_at' => now(),
        ]);

        // Send notification
        NotificationService::send('job_application_submitted', [
            'email' => $request->email,
            'phone' => $request->phone_country_code . $request->phone,
        ], [
            'full_name' => $request->full_name,
            'job_title' => $job->title,
            'job_id' => $job->id,
            'application_id' => $application->id,
        ]);

        return redirect()->back()->with('success', __('Application submitted successfully! We will contact you soon.'));
    }

    /**
     * Generate application form fields for a job
     */
    public static function getApplicationFormFields(): array
    {
        return [
            'full_name' => [
                'type' => 'text',
                'label' => 'Full Name',
                'required' => true,
                'placeholder' => 'Enter your full name',
            ],
            'email' => [
                'type' => 'email',
                'label' => 'Email Address',
                'required' => true,
                'placeholder' => 'your@email.com',
            ],
            'phone' => [
                'type' => 'tel',
                'label' => 'Phone Number',
                'required' => true,
                'placeholder' => 'Phone number',
            ],
            'cv' => [
                'type' => 'file',
                'label' => 'Upload CV/Resume',
                'required' => true,
                'accept' => '.pdf,.doc,.docx',
                'max_size' => 5, // MB
            ],
            'cover_letter' => [
                'type' => 'textarea',
                'label' => 'Cover Letter (Optional)',
                'required' => false,
                'placeholder' => 'Tell us why you are a good fit...',
                'rows' => 5,
            ],
            'consent' => [
                'type' => 'checkbox',
                'label' => 'I agree to the data processing terms and privacy policy',
                'required' => true,
            ],
        ];
    }
}

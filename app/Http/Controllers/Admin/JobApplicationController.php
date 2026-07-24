<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\Job;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = JobApplication::with('job');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('job_id') && $request->job_id) {
            $query->where('job_id', $request->job_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(25);
        $jobs = Job::where('status', 'open')->orderBy('title')->get();
        $statuses = JobApplication::STATUSES;

        return view('admin.job-applications.index', compact('applications', 'jobs', 'statuses'));
    }

    public function create()
    {
        $jobs = Job::where('status', 'open')->orderBy('title')->get();
        $statuses = JobApplication::STATUSES;
        return view('admin.job-applications.create', compact('jobs', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'cover_letter' => 'nullable|string',
            'resume_path' => 'nullable|string|max:500',
            'portfolio_url' => 'nullable|url|max:500',
            'linkedin_url' => 'nullable|url|max:500',
            'status' => 'required|in:new,screening,interview,offer,rejected,withdrawn',
            'notes' => 'nullable|string',
        ]);

        JobApplication::create($validated);

        return redirect()->route('admin.job-applications.index')
            ->with('success', 'Job application created successfully.');
    }

    public function show(JobApplication $jobApplication)
    {
        $jobApplication->load('job');
        return view('admin.job-applications.show', compact('jobApplication'));
    }

    public function edit(JobApplication $jobApplication)
    {
        $jobs = Job::orderBy('title')->get();
        $statuses = JobApplication::STATUSES;
        return view('admin.job-applications.edit', compact('jobApplication', 'jobs', 'statuses'));
    }

    public function update(Request $request, JobApplication $jobApplication)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'cover_letter' => 'nullable|string',
            'resume_path' => 'nullable|string|max:500',
            'portfolio_url' => 'nullable|url|max:500',
            'linkedin_url' => 'nullable|url|max:500',
            'status' => 'required|in:new,screening,interview,offer,rejected,withdrawn',
            'notes' => 'nullable|string',
        ]);

        $jobApplication->update($validated);

        return redirect()->route('admin.job-applications.index')
            ->with('success', 'Job application updated successfully.');
    }

    public function destroy(JobApplication $jobApplication)
    {
        $jobApplication->delete();

        return redirect()->route('admin.job-applications.index')
            ->with('success', 'Job application deleted successfully.');
    }
}

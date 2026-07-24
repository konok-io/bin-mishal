<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Department;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('department');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('type') && $request->type) {
            $query->where('job_type', $request->type);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(20);
        $departments = Department::orderBy('name')->get();
        $jobTypes = Job::TYPES;
        $statuses = Job::STATUSES;

        return view('admin.job-postings.index', compact('jobs', 'departments', 'jobTypes', 'statuses'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $jobTypes = Job::TYPES;
        $statuses = Job::STATUSES;
        $experiences = Job::EXPERIENCE_LEVELS;
        return view('admin.job-postings.create', compact('departments', 'jobTypes', 'statuses', 'experiences'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'job_type' => 'required|in:full_time,part_time,contract,internship,remote',
            'experience_level' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'vacancies' => 'nullable|integer|min:1',
            'application_deadline' => 'nullable|date|after:today',
            'status' => 'required|in:draft,open,closed,on_hold',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['is_featured'] = $validated['is_featured'] ?? false;

        Job::create($validated);

        return redirect()->route('admin.job-postings.index')
            ->with('success', 'Job posting created successfully.');
    }

    public function show(Job $job)
    {
        $job->load('department');
        $applications = $job->applications()->orderBy('created_at', 'desc')->take(10)->get();
        return view('admin.job-postings.show', compact('job', 'applications'));
    }

    public function edit(Job $job)
    {
        $departments = Department::orderBy('name')->get();
        $jobTypes = Job::TYPES;
        $statuses = Job::STATUSES;
        $experiences = Job::EXPERIENCE_LEVELS;
        return view('admin.job-postings.edit', compact('job', 'departments', 'jobTypes', 'statuses', 'experiences'));
    }

    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'job_type' => 'required|in:full_time,part_time,contract,internship,remote',
            'experience_level' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'vacancies' => 'nullable|integer|min:1',
            'application_deadline' => 'nullable|date',
            'status' => 'required|in:draft,open,closed,on_hold',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $job->update($validated);

        return redirect()->route('admin.job-postings.index')
            ->with('success', 'Job posting updated successfully.');
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.job-postings.index')
            ->with('success', 'Job posting deleted successfully.');
    }
}

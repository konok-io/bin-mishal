<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::latest();

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'featured');
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('quote', 'like', '%' . $request->search . '%');
            });
        }

        $testimonials = $query->paginate(25);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'designation_bn' => 'nullable|string|max:255',
            'designation_ar' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'company_bn' => 'nullable|string|max:255',
            'company_ar' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'quote_bn' => 'nullable|string',
            'quote_ar' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'avatar' => 'nullable|string',
            'service_type' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'designation_bn' => 'nullable|string|max:255',
            'designation_ar' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'company_bn' => 'nullable|string|max:255',
            'company_ar' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'quote_bn' => 'nullable|string',
            'quote_ar' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'avatar' => 'nullable|string',
            'service_type' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted successfully.');
    }
}

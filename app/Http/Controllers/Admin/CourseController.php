<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::paginate(10);
        return view('courses.index', compact('courses'));
    }


      public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }


    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        // Validate form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'original_price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'installment' => 'nullable|boolean',
            'installment_1' => 'nullable|numeric',
            'installment_2' => 'nullable|numeric',
            'installment_3' => 'nullable|numeric',
            'installment_4' => 'nullable|numeric',
            'installment_5' => 'nullable|numeric',
            'installment_6' => 'nullable|numeric',
            'start_date' => 'required|date',
            'duration' => 'required|string',
            'image' => 'nullable|image|max:1024', // Image validation
        ]);

        // Auto-generate a course ID
        $course = new Course($validated);
        $course->course_id = Str::random(10);

        // Handle image upload
        if ($request->hasFile('image')) {
            $course->image = $request->file('image')->store('courses', 'public'); // Store image in 'public/courses'
        }

        $course->save();  // Save course to the database

        return redirect()->route('courses.index')->with('success', 'Course added successfully.');
    }





    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    
    public function update(Request $request, Course $course)
{
    // Validate the request data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'original_price' => 'required|numeric',
        'discount_price' => 'nullable|numeric',
        'installment' => 'nullable|boolean',
        'installment_1' => 'nullable|numeric',
        'installment_2' => 'nullable|numeric',
        'installment_3' => 'nullable|numeric',
        'installment_4' => 'nullable|numeric',
        'installment_5' => 'nullable|numeric',
        'installment_6' => 'nullable|numeric',
        'start_date' => 'required|date',
        'duration' => 'required|string',
        'image' => 'nullable|image|max:1024', // Ensure image validation
    ]);

    // Check if a new image was uploaded
    if ($request->hasFile('image')) {
        // Store the new image in the 'public/courses' directory
        $filePath = $request->file('image')->store('courses', 'public');
        $course->image = $filePath; // Update the image path
        $validated['image'] = $course->image;
    } else {
        // If no new image is uploaded, keep the old image path
        $validated['image'] = $course->image;
    }
    

    // Update the course with the validated data
    $course->update($validated);

    return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
}

        


    public function destroy(Course $course)
    {
        $course->update(['status' => 'deleted']);
        
        return redirect()->route('courses.index')->with('success', 'Course status updated to deleted.');
    }




    public function available()
    {
        //$courses = Course::whereIn('status', ['ongoing', 'waiting'])->get();
        return view('courses.available');
    }






}


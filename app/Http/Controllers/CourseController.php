<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\Edufield;
use App\Models\Edugroup;
use App\Models\Knowledge;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('course.index', [
            'courses' => Course::all()/*->sortBy([
                ['grade', 'asc'],
                ['name', 'asc']
            ])->sortByDesc('compulsory')*/
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('course.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code_name' => ['required', 'alpha_dash', 'max:20', 'unique:courses,code_name'],
            'description' => ['required', 'string', 'max:2000'],
            'grade' => ['nullable', 'numeric'],
            'compulsory' => ['nullable', 'boolean'],
        ]);

        $course = Course::create($validated);

        $action = $request->input('action');
        if ($action === 'save_new') {
            return redirect()->route('course.create')->with('notification', __('Course :name created successfully. You can add another course now.', ['name' => $course->name]));
        } else {
            return redirect()->route('course.index')->with('notification', __('Course :name created successfully.', ['name' => $course->name]));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        // orderBy for Eloquent queries // sortBy for Collections // orderBy > sortBy
        return view('course.edit', [
            'course' => $course,
            'edugroups' => Edugroup::orderBy('core', 'desc')
                ->orderBy('name', 'asc')
                ->get(),
            'edufields' => Edufield::orderBy('code_name')
                ->get()
                ->withRelationshipAutoloading()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code_name' => ['required', 'alpha_dash', 'max:20', Rule::unique('courses')->ignore($course)],
            'description' => ['required', 'string', 'max:2000'],
            'grade' => ['nullable', 'numeric'],
            'compulsory' => ['nullable', 'boolean'],
        ]);

        $course->fill($validated);
        $course->save();

        return redirect()->route('course.edit', $course)
            ->with('notification', __('Course :name edited successfully.', ['name' => $course->name]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }

    public function updateEdugroups(Request $request, Course $course) {
        // Validate that users[] is an array of existing IDs
        $validated = $request->validate([
            'edugroups' => 'array',
            'edugroups.*' => 'exists:edugroups,id',
        ]);

        // Sync updates the pivot table — adds new, removes unchecked
        $course->edugroups()->sync($validated['edugroups'] ?? []);

        return redirect()->route('course.edit', $course)->with('notification', 'Groups updated successfully.');
    }

    public function updateKnowledge(Request $request, Course $course) {
        $validated = $request->validate([
            'knowledge' => 'array',
            'knowledge.*' => 'exists:knowledge,id',
        ]);

        // Sync updates the pivot table — adds new, removes unchecked
        $course->knowledge()->sync($validated['knowledge'] ?? []);

        return redirect()->route('course.edit', $course)->with('notification', 'Knowledge updated successfully.');
    }

    public function removeKnowledge(Request $request, Course $course) {
        $knowledgeId = $request->input('knowledge_id');

        // Validate that the knowledge ID exists
        $request->validate([
            'knowledge_id' => 'required|exists:knowledge,id',
        ]);

        $knowledge = Knowledge::findOrFail($knowledgeId);

        // Detach the knowledge from the course
        $course->knowledge()->detach($knowledge);

        return redirect()->route('course.edit', $course)->with('notification', __('Knowledge :name removed successfully.', ['name' => $knowledge->name]));
    }
}

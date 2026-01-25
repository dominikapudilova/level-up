<?php

namespace App\Http\Controllers;

use App\Models\Edugroup;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('student.index', [
            'students' => Student::all()->sortBy([
                ['last_name', 'asc'],
                ['first_name', 'asc'],
            ])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('student.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'alpha_dash', 'min:4', 'max:50', 'unique:students,nickname'],
            'birth_date' => ['required', 'date', Rule::date()->beforeToday()],
            'access_pin' => ['required', 'integer:strict', 'digits:4'],
        ]);

        Student::create($validated);

        $action = $request->input('action');
        if ($action === 'save_new') {
            return redirect()->route('student.create')->with('notification', __('Student created successfully. You can add another student now.'));
        } else {
            return redirect()->route('student.index')->with('notification', __('Student created successfully.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('student.show', [
            'student' => $student
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('student.edit', [
            'student' => $student,
            'edugroups' => Edugroup::orderBy('core', 'desc')
                ->orderBy('name', 'asc')
                ->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'alpha_dash', 'min:4', 'max:50', Rule::unique('students')->ignore($student)],
            'birth_date' => ['required', 'date'],
            'access_pin' => ['required', 'integer:strict', 'digits:4'],
        ]);

        $student->fill($validated);
        $student->save();

        return redirect()->route('student.show', $student)->with('notification', 'Student information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //$student->delete();
    }

    public function setPhoto(Request $request, Student $student) {
        request()->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2mb
        ]);
        $path = $request->file('photo')->store('student_photos', 'public');

        if ($student->photo) {
            // delete old photo
            Storage::disk('public')->delete($student->photo);
        }
        $student->photo = $path;
        $student->save();

        return redirect()->route('student.show', $student)->with('notification', __('Photo updated successfully.'));
    }

    public function updateEdugroups(Request $request, Student $student) {
        // Validate that users[] is an array of existing IDs
        $validated = $request->validate([
            'edugroups' => 'array',
            'edugroups.*' => 'exists:edugroups,id',
        ]);

        // Sync updates the pivot table — adds new, removes unchecked
        $student->edugroups()->sync($validated['edugroups'] ?? []);

        return redirect()->route('student.show', $student)->with('notification', __('Groups updated successfully.'));
    }
}

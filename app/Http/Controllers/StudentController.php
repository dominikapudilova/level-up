<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function manageStudents() {
        return view('student.manage');
    }

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
            'first_name' => ['required', 'alpha_dash', 'max:255'],
            'last_name' => ['required', 'alpha_dash', 'max:255'],
            'nickname' => ['required', 'alpha_dash:ascii', 'max:50', 'unique:students,nickname'],
            'birth_date' => ['required', 'date'],
            'access_pin' => ['required', 'integer:strict', 'digits:4'],
        ]);

        $student = Student::create($validated);

        $action = $request->input('action');
        if ($action === 'save_new') {
            return redirect()->route('student.create')->with('notification', 'Student created successfully. You can add another student now.');
        } else {
            return redirect()->route('student.index')->with('notification', 'Student created successfully.');
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
            'student' => $student
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'alpha_dash', 'max:255'],
            'last_name' => ['required', 'alpha_dash', 'max:255'],
            'nickname' => ['required', 'alpha_dash:ascii', 'max:50', Rule::unique('students')->ignore($student)],
            'birth_date' => ['required', 'date'],
            'access_pin' => ['required', 'integer:strict', 'digits:4'],
        ]);

        $student->fill($validated);
        $student->save();

        redirect()->route('student.show', $student)->with('notification', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}

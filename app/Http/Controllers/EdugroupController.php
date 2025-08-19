<?php

namespace App\Http\Controllers;

use App\Models\Edugroup;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class EdugroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('edugroup.index', [
            'edugroups' => Edugroup::all()->sortBy([
                ['core', 'desc'],
                ['name', 'asc']
            ])

                //->sortByDesc('name'),//->sortBy('year_founded'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('edugroup.create', [
            'teachers' => User::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'year_founded' => ['required', 'integer:strict', 'digits:4'],
            'core' => ['required', 'boolean']
        ]);

        $group = Edugroup::create($validated);

        $action = $request->input('action');
        if ($action === 'save_new') {
            return redirect()->route('edugroup.create')->with('notification', __('Group :name created successfully. You can add another group now.', ['name' => $group->name]));
        } else {
            return redirect()->route('edugroup.index')->with('notification', __('Group :name created successfully.', ['name' => $group->name]));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Edugroup $edugroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Edugroup $edugroup)
    {
        return view('edugroup.edit', [
            'edugroup' => $edugroup,
            'students' => Student::all()->sortBy('last_name'),
//            'teachers' => User::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Edugroup $edugroup)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'year_founded' => ['required', 'integer:strict', 'digits:4'],
            'core' => ['required', 'boolean']
        ]);

        $edugroup->fill($validated);
        $edugroup->save();

        return redirect()->route('edugroup.edit', $edugroup)->with('notification', __('Group :name edited successfully.', ['name' => $edugroup->name]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Edugroup $edugroup)
    {
        //
    }


    public function updateStudents(Request $request, Edugroup $edugroup) {
        // Validate that users[] is an array of existing IDs
        $validated = $request->validate([
            'students' => 'array',
            'students.*' => 'exists:students,id',
        ]);

        // check that a student is not already in a core class
        if ($edugroup->core) {
            foreach ($validated['students'] as $studentId) {
                $student = Student::findOrFail($studentId);

                // Check if the student is already in a core class
                $alreadyInCore = $student->edugroups()
                    ->where('core', true)
                    ->where('edugroup_id', '!=', $edugroup->id) // Exclude the current edugroup
                    ->exists();

                if ($alreadyInCore) {
                    return back()->withErrors(__('Student :name is already assigned to a core class.', ['name' => $student->first_name . ' ' . $student->last_name]))
                        ->withInput();
                }
            }
        }

        // Sync updates the pivot table — adds new, removes unchecked
        $edugroup->students()->sync($validated['students'] ?? []);

        return redirect()->route('edugroup.edit', $edugroup)->with('notification', 'Users updated successfully!');
    }
}

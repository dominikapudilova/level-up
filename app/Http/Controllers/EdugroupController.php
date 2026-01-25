<?php

namespace App\Http\Controllers;

use App\Models\Edugroup;
use App\Http\Controllers\Controller;
use App\Models\KioskSession;
use App\Models\KnowledgeStudent;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // get all knowledge gained per edufield
        $studentIds = $edugroup->students()->pluck('students.id'); // get student list

        $rows = DB::table('knowledge_student')
            ->whereIn('student_id', $studentIds)
            ->join('knowledge', 'knowledge.id', '=', 'knowledge_student.knowledge_id')
            ->join('knowledge_levels', 'knowledge_levels.id', '=', 'knowledge_student.level_id')
            ->join('subcategories', 'subcategories.id', '=', 'knowledge.subcategory_id')
            ->join('categories', 'categories.id', '=', 'subcategories.category_id')
            ->join('edufields', 'edufields.id', '=', 'categories.edufield_id')
            ->select([
                'edufields.id as edufield_id',
                'edufields.name as edufield_name',
                'edufields.code_name as edufield_code',

                'categories.id as category_id',
                'categories.name as category_name',

                'subcategories.id as subcategory_id',
                'subcategories.name as subcategory_name',

                'knowledge.id as knowledge_id',
                'knowledge.name as knowledge_name',
                'knowledge.code_name as knowledge_code',


                'knowledge_levels.id as level_id',
                'knowledge_levels.icon as level_icon',
                'knowledge_levels.name as level_name',
                'knowledge_levels.weight as level_weight',

                DB::raw('COUNT(DISTINCT knowledge_student.student_id) as students_count'),
            ])
            ->groupBy(
                'edufields.id',
                'edufields.name',
                'edufields.code_name',

                'categories.id',
                'categories.name',

                'subcategories.id',
                'subcategories.name',

                'knowledge.id',
                'knowledge.name',
                'knowledge.code_name',

                'knowledge_levels.id',
                'knowledge_levels.icon',
                'knowledge_levels.name',
                'knowledge_levels.weight'
            )
            ->orderBy('edufields.code_name')
            ->orderBy('knowledge.code_name')
            ->orderBy('knowledge_levels.weight')
            ->distinct()
            ->get();

        $gainedKnowledge = $rows->groupBy('edufield_id');

        return view('edugroup.edit', [
            'edugroup' => $edugroup,
            'students' => Student::all()->sortBy('last_name'),
            'gainedKnowledge' => $gainedKnowledge,
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
        // Validate that students[] is an array of existing IDs
        $validated = $request->validate([
            'students' => 'array',
            'students.*' => 'exists:students,id',
        ]);

        // check that a student is not already in a core class
        if ($edugroup->core && isset($validated['students'])) {
            foreach ($validated['students'] as $studentId) {
                $student = Student::findOrFail($studentId);

                // Check if the student is already in a core class
                $coreGroup = $student->edugroups()
                    ->where('core', true)
                    ->where('edugroup_id', '!=', $edugroup->id) // Exclude the current edugroup
                    ->first();

                if ($coreGroup) {
                    return back()->withErrors(__('Student :name is already assigned to a core class (:class).', ['name' => $student->first_name . ' ' . $student->last_name, 'class' => $coreGroup->name]))
                        ->withInput();
                }
            }
        }

        // Sync updates the pivot table - adds new, removes unchecked
        $edugroup->students()->sync($validated['students'] ?? []);

        return redirect()->route('edugroup.edit', $edugroup)->with('notification', __('Student list updated successfully.'));
    }
}

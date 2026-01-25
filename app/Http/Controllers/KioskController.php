<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Edugroup;
use App\Models\KioskSession;
use App\Models\Knowledge;
use App\Models\KnowledgeLevel;
use App\Models\KnowledgeStudent;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KioskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard', [
            'kiosks' => KioskSession::where('teacher_id', auth()->id())
                ->where('ended_at', null)
                ->with(['edugroup', 'course'])
                ->orderBy('started_at', 'desc')
                ->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kiosk.create', [
//            'edufields' => Edufield::all(),
            'edugroups' => Edugroup::all(),
            'courses' => Course::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'edugroup_id' => 'required|exists:edugroups,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $edugroup = Edugroup::findOrFail($validated['edugroup_id']);

        if ($edugroup->students->isEmpty()) {
            return redirect()->route('edugroup.edit', $edugroup)
                ->withErrors(__('There are no students in this group. Add students first before starting.'));
        }

        $kiosk = KioskSession::create([
            'edugroup_id' => $validated['edugroup_id'],
            'course_id' => $validated['course_id'],
            'teacher_id' => auth()->id(),
            'started_at' => now(),
        ]);

        return redirect()->route('kiosk.attendance', $kiosk); //->with('notification', 'Kiosk session created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KioskSession $kiosk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KioskSession $kiosk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KioskSession $kiosk)
    {
        //
    }

    public function endSession(Request $request, KioskSession $kiosk) {
        $teacher = User::findOrFail($kiosk->teacher_id);

        // validate and check user's PIN
        $validated = $request->validate([
            'pin' => 'required|string|min:4|max:20',
        ]);
        $this->checkPin($validated['pin'], $teacher->pin, 'confirmEndSession');

        $kiosk->ended_at = now();
        $kiosk->save();

        // log user back in after closing session
        Auth::loginUsingId($teacher->id);

        return redirect()->route('dashboard')->with('notification', __('Kiosk session ended successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KioskSession $kiosk)
    {
        //
    }


    // ajax search request
    public function searchCourses(Request $request)
    {
        $query = $request->input('edugroup_id');

        // search courses by edugroup_id
        if ($query) {
            $courses = $this->getCoursesForEdugroup($query);
            return response()->json($courses);
        }

        return response()->json([]);
    }

    private function getCoursesForEdugroup($edugroupId) {
        return Course::whereHas('edugroups', function($query) use ($edugroupId) {
            $query->where('edugroup_id', $edugroupId);
        })->get();
    }

    public function attendance(Request $request, KioskSession $kiosk)
    {
        $edugroup = $kiosk->edugroup;
        $students = $edugroup->students;
        $course = $kiosk->course;
        $attendance = Attendance::where('kiosk_session_id', $kiosk->id)
            ->with('student')
            ->get();
        $presentStudents = $attendance->pluck('student.id')->toArray();

        return view('kiosk.attendance', [
            'edugroup' => $edugroup,
            'students' => $students->sortBy('last_name'),
            'presentStudentIds' => $presentStudents,
            'course' => $course,
            'kiosk' => $kiosk,
        ]);
    }

    public function storeAttendance(Request $request, KioskSession $kiosk) {
        $validated = $request->validate([
            'students' => 'array',
            'students.*' => 'exists:students,id',
        ]);

        // do not allow 0 students
        $students = $validated['students'] ?? [];
        if (!$students || count($students) <= 0) {
            return redirect()->route('kiosk.attendance', $kiosk)
                ->withErrors(__('Cannot start a class with zero students.'));
        }

        // sync attendance (delete old, create new records)
        DB::transaction(function () use ($kiosk, $students) {
            Attendance::where('kiosk_session_id', $kiosk->id)->delete();
            foreach ($students as $studentId) {
                Attendance::create([
                    'kiosk_session_id' => $kiosk->id,
                    'student_id' => $studentId,
                ]);
            }
        });

        return redirect()->route('kiosk.session', $kiosk)
            ->with('notification', __('Attendance recorded successfully.'));
    }

    public function kioskSession(Request $request, KioskSession $kiosk) {
        // check active
        if ($kiosk->ended_at) { // || !$kiosk->started_at->isToday() lesson is automatically closed at the end of the day
            return redirect()->route('kiosk.create')
                ->withErrors(__('This class did not start today or it has already ended.'));
        }

        // check attendance
        $attendance = Attendance::where('kiosk_session_id', $kiosk->id)
            ->with('student')
            ->get();

        if ($attendance->isEmpty()) {
            return redirect()->route('kiosk.attendance', $kiosk)
                ->withErrors(__('Cannot start a class with zero students.'));
        }

        // get gained knowledge in this current edugroup and current course
        $gainedKnowledge = $this->getGainedKnowledgeInEdugroupInCourse($kiosk->edugroup, $kiosk->course->id);
        $gainedKnowledge = $gainedKnowledge->groupBy('edufield_id');

        // start...
        $this->startKioskSession($request, $kiosk, auth()->id());

        return view('kiosk.session', [
            'kiosk' => $kiosk,
            'teacherName' => $kiosk->teacher->first_name . ' ' . $kiosk->teacher->last_name,
            'students' => $attendance->pluck('student')->sortBy('last_name'),
            'edugroup' => $kiosk->edugroup,
            'course' => $kiosk->course,
            'edufields' => $this->collectEdufieldsFromCourseKnowledge($kiosk->course),
            'knowledges' => $kiosk->course->knowledge,
            'knowledgeLevels' => KnowledgeLevel::all(),
            'history' => KnowledgeStudent::where('kiosk_id', $kiosk->id)
                ->with(['knowledge', 'level', 'student'])
                ->orderBy('created_at', 'desc')
                ->get(),
            'gainedKnowledge' => $gainedKnowledge,
        ]);
    }

    private function collectEdufieldsFromCourseKnowledge(Course $course) {
        return $course->knowledge
            ->groupBy(fn($knowledge) => $knowledge->subcategory->category->edufield->id)
            ->map(function ($knowledgesByEdufield) {
                $edufield = $knowledgesByEdufield->first()->subcategory->category->edufield;
                $edufield->categories = $knowledgesByEdufield
                    ->groupBy(fn($knowledge) => $knowledge->subcategory->category->id)
                    ->map(function ($knowledgesByCategory) {
                        $category = $knowledgesByCategory->first()->subcategory->category;
                        $category->subcategories = $knowledgesByCategory
                            ->groupBy(fn($knowledge) => $knowledge->subcategory->id)
                            ->map(function ($knowledgesBySubcategory) {
                                $subcategory = $knowledgesBySubcategory->first()->subcategory;
                                $subcategory->items = $knowledgesBySubcategory;
                                return $subcategory;
                            })
                            ->values();
                        return $category;
                    })
                    ->values();
                return $edufield;
            })
            ->values();
    }

    private function getGainedKnowledgeInEdugroupInCourse(Edugroup $edugroup, $courseId) {
        $studentIds = $edugroup->students()->pluck('students.id'); // get student list
        return DB::table('knowledge_student')
            ->whereIn('student_id', $studentIds)
            ->whereExists(function ($q) use ($courseId) {
                $q->select(DB::raw(1))
                    ->from('course_knowledge')
                    ->whereColumn('course_knowledge.knowledge_id', 'knowledge_student.knowledge_id')
                    ->where('course_knowledge.course_id', $courseId);
            })
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
    }

    private function startKioskSession() {
        // log user out
        Auth::guard('web')->logout();
    }

    public function giveKnowledge(Request $request, KioskSession $kiosk) {
        $validated = $request->validate([
            'pin' => 'required|string|min:4|max:20',
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
            'knowledge_id' => 'required|exists:knowledge,id',
            'level_id' => 'required|exists:knowledge_levels,id',
        ]);

        // check user's PIN
        $this->checkPin($validated['pin'], $kiosk->teacher->pin, 'confirmGiveKnowledge');

        // save knowledge for each student
        $knowledge = Knowledge::findOrFail($validated['knowledge_id']);
        $level = KnowledgeLevel::findOrFail($validated['level_id']);
        $studentIds = $validated['students'];

        foreach ($studentIds as $studentId) {
            $student = Student::find($studentId);

            // give knowledge
            KnowledgeStudent::firstOrCreate([
                'student_id' => $student->id,
                'knowledge_id' => $knowledge->id,
                'level_id' => $level->id,
            ], [
                'kiosk_id' => $kiosk->id,
                'issued_by' => $kiosk->teacher_id,
            ]);

            // give experience
            $levelBefore = $student->getLevel();
            $student->exp += $level->weight;

            // give coins for level up
            if ($student->getLevel() > $levelBefore) $student->bucks += config('school.economy.bucks_per_level_up', 1);

            $student->save();
        }

        return redirect()->route('kiosk.session', $kiosk)
            ->with('notification', __('Knowledge given successfully to selected students.'));
    }

    public function giveBucks(Request $request, KioskSession $kiosk) {
        $bucksPerTransaction = config('school.economy.max_bucks_given_per_transaction', 20);
        $validated = $request->validate([
            'pin' => 'required|string|min:4|max:20',
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
            'bucks' => "required|integer|min:-$bucksPerTransaction|max:$bucksPerTransaction",
        ]);

        // check user's PIN
        $this->checkPin($validated['pin'], $kiosk->teacher->pin, 'confirmGiveBucks');

        // give bucks to each student
        $studentIds = $validated['students'];
        $amount = $validated['bucks'];

        foreach ($studentIds as $studentId) {
            $student = Student::find($studentId);

            // give experience (or remove)
            if ($amount < 0 && ($student->bucks + $amount) < 0) {
                $student->bucks = 0;
            } else {
                $student->bucks += $amount;
            }
            $student->save();
        }

        return redirect()->route('kiosk.session', $kiosk)
            ->with('notification', __('Successfully given :amount Brain Bucks to selected students.', ['amount' => $amount]));
    }

    // pages for students ...
    public function selectStudentIndex(Request $request, KioskSession $kiosk) {
        session(['kiosk_student_auth' => null]);

        // get knowledge for students in this kiosk session
        $studentIds = Attendance::where('kiosk_session_id', $kiosk->id)
            ->pluck('student_id')
            ->sortBy('last_name');

        $highestLevels = DB::table('knowledge_student as ks')
            ->join('knowledge_levels as kl', 'kl.id', '=', 'ks.level_id')
            ->whereIn('ks.student_id', $studentIds)
            ->select([
                'ks.student_id',
                'ks.knowledge_id',
                DB::raw('MAX(kl.weight) as max_weight'),
            ])
            ->groupBy('ks.student_id', 'ks.knowledge_id');

        $knowledgeByStudent = DB::table('knowledge_student as ks')
            ->join('knowledge_levels as kl', 'kl.id', '=', 'ks.level_id')
            ->joinSub($highestLevels, 'max_levels', function ($join) {
                $join->on('ks.student_id', '=', 'max_levels.student_id')
                    ->on('ks.knowledge_id', '=', 'max_levels.knowledge_id')
                    ->on('kl.weight', '=', 'max_levels.max_weight');
            })
            ->join('knowledge', 'knowledge.id', '=', 'ks.knowledge_id')
            ->select([
                'ks.student_id',

                'knowledge.id as knowledge_id',
                'knowledge.name as knowledge_name',

                'kl.id as level_id',
                'kl.name as level_name',
                'kl.weight as level_weight',
                'kl.icon as level_icon',
            ])
            ->orderBy('kl.weight', 'desc')
            ->orderBy('knowledge.name')
            ->get()
            ->groupBy('student_id');

        $attendance = Attendance::where('kiosk_session_id', $kiosk->id)
            ->with('student')
            ->get()
            ->map(function ($row) use ($knowledgeByStudent) {
                $row->knowledge = $knowledgeByStudent[$row->student_id] ?? collect();
                return $row;
            });

        return view('kiosk.select-student-index', [
            'currentStudents' => $attendance,
            'kiosk' => $kiosk,
        ]);
    }

    public function editStudent(Request $request, KioskSession $kiosk, Student $student) {
        // validate and check student's PIN
        $validated = $request->validate([
            'pin' => 'required|string|min:4|max:20',
        ]);
        $this->checkPin($validated['pin'], $student->access_pin);

        session([ 'kiosk_student_auth' => $student->id ]); // todo: takto? nebo _id => true

        return redirect()->route('kiosk.student.edit-index', [$kiosk, $student]);
    }

    public function editStudentIndex(Request $request, KioskSession $kiosk, Student $student) {
        if (session('kiosk_student_auth') !== $student->id) {
            return redirect()->route('kiosk.student.index', $kiosk)
                ->withErrors(__('Please verify your PIN to access student settings.'));
        }

        return view('kiosk.edit-student', [
            'kiosk' => $kiosk,
            'student' => $student,
        ]);
    }

    public function purchasePfp(Request $request, KioskSession $kiosk, Student $student) {
        // validate and check student's PIN
        $validated = $request->validate([
            'pin' => 'required|string|min:4|max:20',
            'pfp' => 'required|string|max:255',
        ]);
        $this->checkPin($validated['pin'], $student->access_pin);
        $pfp = $validated['pfp'];

        // check if not the same
        if ($pfp == $student->avatar) {
            return redirect()->route('kiosk.student.edit-index', [$kiosk, $student])
                ->withErrors(__('You cannot purchase a profile picture you already own.'));
        }

        // check enough bucks
        if ($pfp === 'random') {
            $pfpCost = config('school.economy.prices.profile_picture_random', 5);
            $pfp = collect(config('school.cosmetics.avatars'))
                ->filter(fn($avatar) => $avatar !== $student->avatar)
                ->random();
        } else {
            $pfpCost = config('school.economy.prices.profile_picture', 10);
        }

        if ($student->bucks < $pfpCost) {
            return redirect()->route('kiosk.student.edit-index', [$kiosk, $student])
                ->withErrors(__('You do not have enough Brain Bucks to purchase this profile picture.'));
        }

        // deduct bucks
        $student->bucks -= $pfpCost;

        // set new pfp
        $student->avatar = $pfp;
        $student->save();

        return redirect()
            ->route('kiosk.student.edit-index', [$kiosk, $student])
            ->with('notification', __('Profile picture changed successfully.'));
    }

    public function purchaseBg(Request $request, KioskSession $kiosk, Student $student) {
        $validated = $request->validate([
            'pin' => 'required|string|min:4|max:20',
            'bg' => 'required|string|max:255',
        ]);
        $this->checkPin($validated['pin'], $student->access_pin);
        $bg = $validated['bg'];

        // check if not the same
        if ($bg == $student->background_image) {
            return redirect()->route('kiosk.student.edit-index', [$kiosk, $student])
                ->withErrors(__('You cannot purchase a background you already own.'));
        }

        // check enough bucks
        if ($bg === 'random') {
            $bgCost = config('school.economy.prices.background_random', 10);
            $bg = collect(config('school.cosmetics.backgrounds'))
                ->filter(fn($bg) => $bg !== $student->background_image)
                ->random();
        } else {
            $bgCost = config('school.economy.prices.background', 15);
        }

        if ($student->bucks < $bgCost) {
            return redirect()->route('kiosk.student.edit-index', [$kiosk, $student])
                ->withErrors(__('You do not have enough Brain Bucks to purchase this background.'));
        }

        // deduct bucks
        $student->bucks -= $bgCost;

        // set new pfp
        $student->background_image = $bg;
        $student->save();

        return redirect()
            ->route('kiosk.student.edit-index', [$kiosk, $student])
            ->with('notification', __('Background changed successfully.'));
    }

    public function purchaseRename(Request $request, KioskSession $kiosk, Student $student) {
        $validated = $request->validate([
            'pin' => 'required|string|min:4|max:20',
            'nickname' => 'required|alpha_dash|min:4|max:50|unique:students,nickname',
        ]);
        $this->checkPin($validated['pin'], $student->access_pin);
        $nickname = $validated['nickname'];

        // check enough bucks
        $renameCost = config('school.economy.prices.rename', 40);
        if ($student->bucks < $renameCost) {
            return redirect()->route('kiosk.student.edit-index', [$kiosk, $student])
                ->withErrors(__('You do not have enough Brain Bucks to purchase a nickname.'));
        }

        // deduct bucks
        $student->bucks -= $renameCost;

        // set new pfp
        $student->nickname = $nickname;
        $student->save();

        return redirect()
            ->route('kiosk.student.edit-index', [$kiosk, $student])
            ->with('notification', __('Nickname changed successfully.'));
    }

    public function changePin(Request $request, KioskSession $kiosk, Student $student) {
        $validated = $request->validate([
            'pin' => 'required|confirmed|string|min:4|max:20',
            'pin_old' => 'required|string|min:4|max:20',
        ]);
        $this->checkPin($validated['pin_old'], $student->access_pin);
        $pin = $validated['pin'];

        // set new pin
        $student->access_pin = $pin;
        $student->save();

        return redirect()
            ->route('kiosk.student.edit-index', [$kiosk, $student])
            ->with('notification', __('PIN changed successfully.'));
    }

    public function purchaseTheme(Request $request, KioskSession $kiosk, Student $student) {
        $validated = $request->validate([
            'pin' => 'required|string|min:4|max:20',
            'theme' => 'required|string|in:' . implode(',', config('school.cosmetics.themes', [])),
        ]);
        $this->checkPin($validated['pin'], $student->access_pin);
        $theme = $validated['theme'];

        if ($theme == $student->theme) {
            return redirect()->route('kiosk.student.edit-index', [$kiosk, $student])
                ->withErrors(__('You cannot purchase a theme you already own.'));
        }

        // check enough bucks
        $themeCost = config('school.economy.prices.theme', 30);
        if ($student->bucks < $themeCost) {
            return redirect()->route('kiosk.student.edit-index', [$kiosk, $student])
                ->withErrors(__('You do not have enough Brain Bucks to purchase this theme.'));
        }

        // deduct bucks
        $student->bucks -= $themeCost;

        // set new theme
        $student->theme = $theme;
        $student->save();

        return redirect()
            ->route('kiosk.student.edit-index', [$kiosk, $student])
            ->with('notification', __('Theme changed successfully.'));
    }

    private function checkPin($pin, $usersPin, $errorBag = 'default') {
        // check user's PIN
        if ($pin !== $usersPin) {
            throw ValidationException::withMessages(['pin' => __('The provided PIN is incorrect.')])->errorBag($errorBag);
        }
    }
}

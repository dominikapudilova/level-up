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
            // other validation rules as needed
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

        // start kiosk session (logout current user, store kiosk session id in session)
//        $teacherId = auth()->user();
//        $this->startKioskSession($request, $kiosk, $teacherId);

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

        // if ending currently running session, forget. Otherwise, it is just ending an older session.
        /*if (session('kiosk_session_id') == $kiosk->id) {
            session()->forget('kiosk_session_id');
        }*/

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

        // search courses by something else?
        // ...

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

        // start...
        $this->startKioskSession($request, $kiosk, auth()->id());

        return view('kiosk.session', [
            'kiosk' => $kiosk,
            'teacherName' => $kiosk->teacher->first_name . ' ' . $kiosk->teacher->last_name,
            'students' => $attendance->pluck('student')->sortBy('last_name'),
            'edugroup' => $kiosk->edugroup,
            'course' => $kiosk->course,
//            'edufields' => Edufield::orderBy('name')->get(),
            'edufields' => $this->collectEdufieldsFromCourseKnowledge($kiosk->course),
            'knowledgeLevels' => KnowledgeLevel::all(),
            'history' => KnowledgeStudent::where('kiosk_id', $kiosk->id)
                ->with(['knowledge', 'level', 'student'])
                ->orderBy('created_at', 'desc')
                ->get(),
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

    private function startKioskSession(Request $request, KioskSession $kiosk, $userId) { // todo: upravit?
        // log user out
        Auth::guard('web')->logout();
//        $request->session()->invalidate();
//        $request->session()->regenerateToken();
//        $request->session()->regenerate();

        // save info to session -> it is already saved in the kiosk object
//        $request->session()->put('kiosk_session_id', $kiosk->id);
//        $request->session()->put('teacher_id', $userId);
//        session(['kiosk_session_id' => $kiosk->id]);
//        session(['teacher_id' => $userId]);
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
        $attendance = Attendance::where('kiosk_session_id', $kiosk->id)
            ->with('student')
            ->get();

        session(['kiosk_student_auth' => null]);

        return view('kiosk.select-student-index', [
            'students' => $attendance->pluck('student')->sortBy('last_name'),
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

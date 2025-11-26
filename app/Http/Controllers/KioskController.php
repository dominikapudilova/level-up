<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Edugroup;
use App\Models\KioskSession;
use App\Models\KnowledgeLevel;
use App\Models\KnowledgeStudent;
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
        return view('kiosk.index', [
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

        if ($validated['pin'] !== $teacher->pin) {
            throw ValidationException::withMessages(['pin' => __('The provided PIN is incorrect.')]);
        }

        $kiosk->ended_at = now();
        $kiosk->save();

        // log user back in after closing session
        Auth::loginUsingId($teacher->id);

        return redirect()->route('kiosk.index')->with('notification', __('Kiosk session ended successfully.'));
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
            'knowledgeLevels' => KnowledgeLevel::all()
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

    private function startKioskSession(Request $request, KioskSession $kiosk, $userId) {
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
        if ($validated['pin'] !== $kiosk->teacher->pin) {
            throw ValidationException::withMessages(['pin' => __('The provided PIN is incorrect.')]);
        }

        // save knowledge for each student
        $knowledgeId = $validated['knowledge_id'];
        $levelId = $validated['level_id'];
        $studentIds = $validated['students'];

        foreach ($studentIds as $studentId) {
            // give knowledge
            KnowledgeStudent::firstOrCreate([
                'student_id' => $studentId,
                'knowledge_id' => $knowledgeId,
                'level_id' => $levelId,
            ], [
                'kiosk_id' => $kiosk->id,
                'issued_by' => $kiosk->teacher_id,
            ]);
        }

        // todo: (dodělat shrnutí), stránka kiosku pro žáky
        return redirect()->route('kiosk.session', $kiosk)
            ->with('notification', __('Knowledge given successfully to selected students.'));
    }

    /*public function logout(Request $request) {
        session()->forget('kiosk_session_id');
        return redirect()->route('kiosk.index')->with('notification', __('You have been logged out from the kiosk.'));
    }*/
}

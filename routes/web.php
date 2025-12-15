<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EdufieldController;
use App\Http\Controllers\EdugroupController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\KnowledgeLevelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
//    return view('welcome');
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // todo: for admin only
    Route::resource('user', UserController::class)->names('user')
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

    Route::get('/students', [StudentController::class, 'manageStudents'])->name('students.manage');
    Route::resource('student', StudentController::class)->names('student')
        ->only(['index', 'create', 'store', 'show', 'edit', 'update']); //destroy
    Route::put('/student/{student}/update-edugroups', [StudentController::class, 'updateEdugroups'])->name('student.update-edugroups');

    Route::resource('edugroup', EdugroupController::class)->names('edugroup')
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::put('/edugroup/{edugroup}/update-students', [EdugroupController::class, 'updateStudents'])->name('edugroup.update-students');

    Route::resource('course', CourseController::class)->names('course')
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::put('/course/{course}/update-edugroups', [CourseController::class, 'updateEdugroups'])->name('course.update-edugroups');
    Route::put('/course/{course}/update-knowledge', [CourseController::class, 'updateKnowledge'])->name('course.update-knowledge');
    Route::delete('/course/{course}/remove-knowledge', [CourseController::class, 'removeKnowledge'])->name('course.remove-knowledge');

    Route::resource('edufield', EdufieldController::class)->names('edufield')
        ->only(['create', 'store', 'edit', 'update', 'destroy']); //'index', 'show',

    Route::resource('category', CategoryController::class)->names('category')
        ->only(['create', 'store', 'edit', 'update', 'destroy']); //'index', 'show',

    Route::resource('subcategory', SubcategoryController::class)->names('subcategory')
        ->only(['create', 'store', 'edit', 'update', 'destroy']); //'index', 'show',

    Route::resource('knowledge', KnowledgeController::class)->names('knowledge')
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']); //, 'show', , ,

    Route::resource('kiosk', KioskController::class)->names('kiosk')
        ->only(['index', 'create', 'store']); //'show', 'edit', 'update', 'destroy'

    Route::get('kiosk/search-courses', [KioskController::class, 'searchCourses'])->name('kiosk.search-courses');

    Route::resource('knowledge-level', KnowledgeLevelController::class)->names('knowledge-level')
        ->only(['index', 'update']);
});

Route::prefix('kiosk')->group(function () {
    // verify pin
//    Route::get('/{kiosk}/login', [KioskController::class, 'showLogin']);
//    Route::post('/{kiosk}/login', [KioskController::class, 'verifyPin']);
//    Route::post('/{kiosk}/logout', [KioskController::class, 'logout']);

    // kiosk attendance
    Route::get('/{kiosk}/attendance', [KioskController::class, 'attendance'])->name('kiosk.attendance');
    Route::post('/{kiosk}/attendance', [KioskController::class, 'storeAttendance'])->name('kiosk.store-attendance');
//    Route::get('/{kiosk}/attendance/edit', [KioskController::class, 'editAttendance'])->name('kiosk.edit-attendance');

    // kiosk session
    Route::get('/{kiosk}/session', [KioskController::class, 'kioskSession'])->name('kiosk.session');
    Route::post('/{kiosk}/knowledge', [KioskController::class, 'giveKnowledge'])->name('kiosk.give-knowledge');
//    Route::post('/{kiosk}/session-end', [KioskController::class, 'endSession'])->name('kiosk.session-end');
    Route::patch('/{kiosk}/end', [KioskController::class, 'endSession'])->name('kiosk.end');
    Route::post('/{kiosk}/bucks', [KioskController::class, 'giveBucks'])->name('kiosk.give-bucks');

    // kiosk student actions (edit profile, update pin, make a purchase etc.)
    Route::get('/{kiosk}/student', [KioskController::class, 'selectStudentIndex'])->name('kiosk.student.index');
//    Route::get('/{kiosk}/student/{student}', [KioskController::class, 'showStudent'])->name('kiosk.student.show');
    Route::post('/{kiosk}/student/{student}/edit', [KioskController::class, 'editStudent'])->name('kiosk.student.edit');
    Route::get('/{kiosk}/student/{student}/edit', [KioskController::class, 'editStudentIndex'])->name('kiosk.student.edit-index');

    Route::post('/{kiosk}/student/{student}/purchase-pfp', [KioskController::class, 'purchasePfp'])->name('kiosk.student.purchase-pfp');
    Route::post('/{kiosk}/student/{student}/purchase-bg', [KioskController::class, 'purchaseBg'])->name('kiosk.student.purchase-bg');
    Route::post('/{kiosk}/student/{student}/purchase-theme', [KioskController::class, 'purchaseTheme'])->name('kiosk.student.purchase-theme');
    Route::post('/{kiosk}/student/{student}/purchase-rename', [KioskController::class, 'purchaseRename'])->name('kiosk.student.purchase-rename');
    Route::post('/{kiosk}/student/{student}/change-pin', [KioskController::class, 'changePin'])->name('kiosk.student.change-pin');
});



require __DIR__.'/auth.php';

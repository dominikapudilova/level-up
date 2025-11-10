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


    Route::get('/students', [StudentController::class, 'manageStudents'])->name('students.manage');
    Route::resource('student', StudentController::class)->names('student')
        ->only(['index', 'create', 'store', 'show', 'edit', 'update']); //destroy

    Route::resource('edugroup', EdugroupController::class)->names('edugroup')
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::put('/edugroup/{edugroup}/update-students', [EdugroupController::class, 'updateStudents'])->name('edugroup.update-students');

    Route::resource('course', CourseController::class)->names('course')
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::put('/course/{course}/update-edugroups', [CourseController::class, 'updateEdugroups'])->name('course.update-edugroups');
    Route::put('/course/{course}/update-knowledge', [CourseController::class, 'updateKnowledge'])->name('course.update-knowledge');
    Route::delete('/course/{course}/remove-knowledge', [CourseController::class, 'removeKnowledge'])->name('course.remove-knowledge');

    Route::resource('edufield', EdufieldController::class)->names('edufield')
        ->only(['create', 'store', 'edit', 'update']); //'index', 'show', 'destroy'

    Route::resource('category', CategoryController::class)->names('category')
        ->only(['create', 'store', 'edit', 'update']); //'index', 'show', 'destroy'

    Route::resource('subcategory', SubcategoryController::class)->names('subcategory')
        ->only(['create', 'store', 'edit', 'update']); //'index', 'show', 'destroy'

    Route::resource('knowledge', KnowledgeController::class)->names('knowledge')
        ->only(['index', 'create', 'store', 'edit', 'update']); //, 'show', , , 'destroy'

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

    // kiosk student actions (edit profile, update pin, make a purchase etc.)


});



require __DIR__.'/auth.php';

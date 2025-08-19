<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EdufieldController;
use App\Http\Controllers\EdugroupController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubcategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
//    return view('welcome');
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
//    request()->session()->flash('notification', 'Welcome to the dashboard!');
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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
        ->only(['create', 'store']); //'index', 'show', 'edit', 'update', 'destroy'

    Route::resource('category', CategoryController::class)->names('category')
        ->only(['create', 'store']); //'index', 'show', 'edit', 'update', 'destroy'

    Route::resource('subcategory', SubcategoryController::class)->names('subcategory')
        ->only(['create', 'store']); //'index', 'show', 'edit', 'update', 'destroy'

    Route::resource('knowledge', KnowledgeController::class)->names('knowledge')
        ->only(['index', 'create', 'store', 'edit', 'update']); //, 'show', , , 'destroy'
});




require __DIR__.'/auth.php';

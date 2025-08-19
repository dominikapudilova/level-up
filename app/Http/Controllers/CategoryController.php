<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Edufield;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ziskat edufield
        $edufieldId = request('edufield');
        $edufield = Edufield::findOrFail($edufieldId);

        return view('category.create', [
            'edufield' => $edufield,
            'categories' => Category::all()->where('edufield', $edufield)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code_name' => ['required', 'alpha_dash', 'max:20', 'unique:categories,code_name'],
            'description' => ['required', 'string', 'max:2000'],
            'edufield_id' => ['required', 'exists:edufields,id'],
        ]);

        $originCourse = $request->get('course');

        $category = Category::create($validated);
        return redirect()->route('course.edit', ['course' => $originCourse])
            ->with('notification', __('Category :name created successfully.', ['name' => $category->name]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}

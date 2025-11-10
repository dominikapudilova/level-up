<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Edufield;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        $categories = Category::all()->where('edufield', $edufield);

        return view('category.create', [
            'edufield' => $edufield,
            'categoriesImploded' => $categories->implode('name', ', ')
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

        $category = Category::create($validated);
        $originCourse = $request->get('course');

        if (!$originCourse) {
            return redirect()->route('knowledge.index')
                ->with('notification', __('Category :name created successfully.', ['name' => $category->name]));
        }
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
        return view('category.edit', [
            'category' => $category,
            'edufields' => Edufield::all()->sortBy('name')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code_name' => ['required', 'alpha_dash', 'max:20', Rule::unique('categories')->ignore($category)],
            'description' => ['required', 'string', 'max:2000'],
            'edufield_id' => ['required', 'exists:edufields,id'],
        ]);

        $category->fill($validated);
        $category->save();

        $originCourse = $request->get('course');

        if (!$originCourse) {
            return redirect()->route('knowledge.index')
                ->with('notification', __('Category :name updated successfully.', ['name' => $category->name]));
        }
        return redirect()->route('course.edit', ['course' => $originCourse])
            ->with('notification', __('Category :name updated successfully.', ['name' => $category->name]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}

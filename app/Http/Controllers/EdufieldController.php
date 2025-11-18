<?php

namespace App\Http\Controllers;

use App\Models\Edufield;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EdufieldController extends Controller
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
    public function create(Request $request)
    {
        return view('edufield.create', [
            'edufields' => Edufield::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code_name' => ['required', 'alpha_dash', 'max:20', 'unique:edufields,code_name'],
            'description' => ['required', 'string', 'max:2000'],
        ]);

        $edufield = Edufield::create($validated);
        $originCourse = $request->get('course');

        if(!$originCourse) {
            return redirect()->route('knowledge.index')
                ->with('notification', __('Education field :name created successfully.', ['name' => $edufield->name]));
        }
        return redirect()->route('course.edit', ['course' => $originCourse])
            ->with('notification', __('Education field :name created successfully.', ['name' => $edufield->name]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Edufield $edufield)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Edufield $edufield)
    {
        return view('edufield.edit', [
            'edufield' => $edufield
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Edufield $edufield)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code_name' => ['required', 'alpha_dash', 'max:20', Rule::unique('edufields')->ignore($edufield)],
            'description' => ['required', 'string', 'max:2000'],
        ]);

        $edufield->fill($validated);
        $edufield->save();

        $originCourse = $request->get('course');

        if (!$originCourse) {
            return redirect()->route('knowledge.index')
                ->with('notification', __('Education field :name updated successfully.', ['name' => $edufield->name]));
        }
        return redirect()->route('course.edit', ['course' => $originCourse])
            ->with('notification', __('Education field :name updated successfully.', ['name' => $edufield->name]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Edufield $edufield)
    {
        $edufieldName = $edufield->name;
        $edufield->delete();

        $originCourse = $request->get('course');
        if (!$originCourse) {
            return redirect()->route('knowledge.index')
                ->with('notification', __('Education field :name deleted successfully.', ['name' => $edufieldName]));
        }
        return redirect()->route('course.edit', ['course' => $originCourse])
            ->with('notification', __('Education field :name deleted successfully.', ['name' => $edufieldName]));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }
        return view('user.index', [
            'users' => User::all()->sortBy([
                ['last_name', 'asc'],
                ['first_name', 'asc'],
            ])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'username' => ['string', 'min:4', 'max:50', Rule::unique('users')],
            'pin' => ['string', 'min:4', 'max:20'],
            'password' => ['confirmed', 'min:6'],
            'is_admin' => ['boolean'],
        ]);

        User::create($validated);

        return redirect()->route('user.index')->with('notification', 'New user created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }
        return view('user.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }
        return view('user.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }
        $validated = $request->validate([
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'username' => ['string', 'min:4', 'max:50', Rule::unique('users')->ignore($user)],
            'pin' => ['confirmed', 'string', 'min:4', 'max:20'],
            'password' => ['confirmed', 'min:6'],
            'is_admin' => ['boolean'],
        ]);

        if (auth()->user() == $user && (isset($validated['isAdmin']) && $validated['isAdmin'] == false)) {
            return redirect()->route('user.edit', $user)->withErrors(__('You cannot remove your own administrator rights.'));
        }

        $user->fill($validated);
        $user->save();

        return redirect()->route('user.show', $user)->with('notification', 'User information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        if (!Gate::allows('admin')) {
            abort(403);
        }
        if (auth()->user() == $user) {
            return redirect()->route('user.edit', $user)->withErrors(__('You cannot delete your own account.'));
        }

        $request->validate([
            'password' => ['confirmed', 'current_password'],
        ]);

        $user->delete();
        return redirect()->route('user.index')->with('notification', 'User deleted successfully.');
    }
}

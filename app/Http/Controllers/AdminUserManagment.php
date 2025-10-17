<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminUserManagment extends Controller
{

    protected $availableRoles = ['tenant', 'landlord', 'admin'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);

        return view('admin.user_managment.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->availableRoles;
        return view('admin.user_managment.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'CNIC' => 'required|string|max:15|unique:users,CNIC',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', 'string', Rule::in($this->availableRoles)],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'CNIC' => $request->CNIC,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User created and role assigned successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = $this->availableRoles;
        return view('admin.user_managment.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', 'string', Rule::in($this->availableRoles)],
        ]);

        $data = $request->only('name', 'email');
        if($request->filled('password')){
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        // synchronize user role
        $user->syncRoles([$request->role]);
        return redirect()->route('users.index')->with('success', 'User updated and role synchronized successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if($user->id == Auth::id()){
          return back()->with('error', 'Cannot delete your own admin account.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}

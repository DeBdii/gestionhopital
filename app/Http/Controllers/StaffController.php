<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{

    public function index()
    {
        $staffMembers = User::whereIn('user_type', ['Receptionist', 'SupportStaff', 'Nurse'])->get();
        return $staffMembers;
    }


    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'salary' => 'required|numeric|min:0',
            'user_type' => 'required|in:Receptionist,Nurse,SupportStaff',


        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->user_type = $validatedData['user_type'];
        $user->password = bcrypt($request->password);
        $user->salary = $validatedData['salary'];

        $user->save();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully.');
    }


    public function show($id)
    {
        $staffMember = User::findOrFail($id);
        return view('admin.staff.show', compact('staffMember'));
    }


    public function edit($id)
    {
        $staffMember = User::findOrFail($id);
        return view('admin.staff_management', compact('staffMember'));
    }

    public function update(Request $request, $id)
    {
        $staff = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|max:50',
            'salary' => 'required|numeric',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $staff->update($validated);

        return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy($id)
    {
        $staffMember = User::findOrFail($id);
        $staffMember->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member deleted successfully.');
    }
}

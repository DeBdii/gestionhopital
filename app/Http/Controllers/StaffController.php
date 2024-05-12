<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    // Index (List all staff members)
    public function index()
    {
        $staffMembers = User::where('user_type', 'Staff')->get();
        return $staffMembers;
    }

    // Create (Show the form to create a new staff member)
    public function create()
    {
        return view('staff.create');
    }

    // Store (Save the newly created staff member)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'salary' => 'required|numeric|min:0',
            // Add more validation rules as needed
        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->user_type = 'Staff'; // Predefine the user type as 'Staff'
        $user->password = bcrypt($request->password);

        $user->save();

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
    }

    // Show (Display a specific staff member)
    public function show($id)
    {
        $staffMember = User::findOrFail($id);
        return view('staff.show', compact('staffMember'));
    }

    // Edit (Show the form to edit a staff member)
    public function edit($id)
    {
        $staffMember = User::findOrFail($id);
        return view('staff.edit', compact('staffMember'));
    }

    // Update (Update the details of a specific staff member)
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users,email,'.$id,
            // Add more validation rules as needed
        ]);

        $staffMember = User::findOrFail($id);
        $staffMember->update($validatedData);

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
    }

    // Destroy (Delete a specific staff member)
    public function destroy($id)
    {
        $staffMember = User::findOrFail($id);
        $staffMember->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;
use App\Models\User; // Corrected use statement

use Illuminate\Http\Request;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
class DoctorController extends Controller
{
    // Index (List all doctors)
    public function index()
    {

    }

    // Create (Show the form to create a new doctor)
    public function create()
    {
        return view('doctors.create');
    }

    // Store (Save the newly created doctor)


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'specialty' => 'nullable|string|max:255',
            'salary' => 'required|numeric|min:0',
        ]);

        // Create the new user
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->specialty = $validatedData['specialty'];
        $user->salary = $validatedData['salary'];
        $user->user_type = 'Doctor'; // Predefine the user type as 'Doctor'
        $user->password = bcrypt($request->password);

        $user->save();

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor created successfully.');
    }

    // Show (Display a specific doctor)
    public function show($id)
    {
        $doctor = User::findOrFail($id);
        return view('doctors.show', compact('doctor'));
    }

    // Edit (Show the form to edit a doctor)
    public function edit($id)
    {
        $doctor = User::findOrFail($id);
        return view('doctors.edit', compact('doctor'));
    }

    // Update (Update the details of a specific doctor)
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users,email,'.$id,
            'specialty' => 'nullable|string|max:255',
            'salary' => 'required|numeric|min:0',
        ]);

        $doctor = User::findOrFail($id);
        $doctor->update($validatedData);

        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
    }

    // Destroy (Delete a specific doctor)
    public function destroy($id)
    {
        $doctor = User::findOrFail($id);
        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully.');
    }


    public function showCalender()
    {
        $user = Auth::user();
        // Fetch all shifts
        $shifts = Shift::with('users')->get();


        // Fetch the IDs of the shifts associated with the logged-in user
        $userShifts = $user->shifts->pluck('id')->toArray();

        return view('doctor.schedule.index', compact('shifts', 'userShifts'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use App\Models\Stock;

class AdminController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    //PARTIE DOCTORS
    public function manageDoctors(DoctorController $doctorController)
    {
        // Retrieve all doctors using DoctorController's index method
        $doctors = $doctorController->index();
        // Return view for admin's doctor management page
        return view('admin.doctor_management', compact('doctors'));
    }
    public function createDoctor(DoctorController $doctorController)
    {
        // Return view for creating a new doctor using DoctorController's create method
        return $doctorController->create();
    }
    public function storeDoctor(Request $request, DoctorController $doctorController)
    {
        // Call DoctorController's store method to save the newly created doctor
        return $doctorController->store($request);
    }
    public function editDoctor($id, DoctorController $doctorController)
    {
        // Return view for editing a doctor using DoctorController's edit method
        return $doctorController->edit($id);
    }
    public function updateDoctor(Request $request, $id, DoctorController $doctorController)
    {
        // Call DoctorController's update method to update the details of the doctor
        return $doctorController->update($request, $id);
    }
    public function deleteDoctor($id, DoctorController $doctorController)
    {
        // Call DoctorController's destroy method to delete the doctor
        return $doctorController->destroy($id);
    }





    //PARTIE STAFF
    public function manageStaff(StaffController $staffController)
    {
        // Retrieve all staff using StaffController's index method
        $staffMembers = $staffController->index();
        // Return view for admin's staff management page
        return view('admin.staff_management', compact('staffMembers'));
    }

    public function createStaff(StaffController $staffController)
    {
        // Return view for creating a new staff using StaffController's create method
        return $staffController->create();
    }

    public function storeStaff(Request $request, StaffController $staffController)
    {
        // Call StaffController's store method to save the newly created staff
        return $staffController->store($request);
    }

    public function editStaff($id, StaffController $staffController)
    {
        // Return view for editing a staff using StaffController's edit method
        return $staffController->edit($id);
    }

    public function updateStaff(Request $request, $id, StaffController $staffController)
    {
        // Call StaffController's update method to update the details of the staff
        return $staffController->update($request, $id);
    }

    public function deleteStaff($id, StaffController $staffController)
    {
        // Call StaffController's destroy method to delete the staff
        return $staffController->destroy($id);
    }





    //PARTIE DEPARTEMENTS

    public function manageDepartments()
    {
        $departments = Department::all();
        return view('admin.departments.index', compact('departments'));
    }

    // Show the form to create a new department
    public function createDepartment()
    {
        $doctors = User::where('user_type', 'Doctor')->get();
        $stockItems = Stock::all();
        return view('admin.departments.create', compact('doctors', 'stockItems'));
    }

    // Store a newly created department
    public function storeDepartment(Request $request)
    {
        $validatedData = $request->validate([
            'department_name' => 'required|string|max:100',
            'doctors_id' => 'nullable|exists:users,id',
            'item_id' => 'nullable|exists:stocks,id',
        ]);

        Department::create($validatedData);

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    // Show the form to edit a department
    public function editDepartment($id)
    {
        $department = Department::findOrFail($id);
        $doctors = User::where('user_type', 'Doctor')->get();
        $stockItems = Stock::all();
        return view('admin.departments.edit', compact('department', 'doctors', 'stockItems'));
    }

    // Update the details of a department
    public function updateDepartment(Request $request, $id)
    {
        $validatedData = $request->validate([
            'department_name' => 'required|string|max:100',
            'doctors_id' => 'nullable|exists:users,id',
            'item_id' => 'nullable|exists:stocks,id',
        ]);

        $department = Department::findOrFail($id);
        $department->update($validatedData);

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }

    // Delete a department
    public function deleteDepartment($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully.');
    }



}

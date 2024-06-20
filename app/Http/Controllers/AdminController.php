<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use App\Models\Item;
use App\Models\Shift;
use App\Models\DepartmentItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        $doctors = User::where('user_type', 'Doctor')->get();

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
        $departments = Department::with('users', 'items')->get();
        $doctors = User::where('user_type', 'Doctor')->whereDoesntHave('department')->get();
        $items = Item::all();

        return view('admin.departments.index', compact('departments', 'doctors', 'items'));
    }

// Store a newly created department
    public function storeDepartment(Request $request)
    {
        $validatedData = $request->validate([
            'department_name' => 'required|string|max:100',
            'doctors' => 'nullable|array',
            'doctors.*' => 'exists:users,id',
            'items' => 'nullable|array',
            'items.*' => 'exists:items,id',
        ]);

        // Create the department
        $department = Department::create(['department_name' => $validatedData['department_name']]);

        // Assign doctors to the department if selected
        if (isset($validatedData['doctors'])) {
            foreach ($validatedData['doctors'] as $doctorId) {
                $doctor = User::find($doctorId);
                $doctor->department_id = $department->id;
                $doctor->save();
            }
        }

        // Assign items to the department if selected
        if (isset($validatedData['items'])) {
            foreach ($validatedData['items'] as $itemId) {
                DB::table('department_items')->insert([
                    'department_id' => $department->id,
                    'item_id' => $itemId,
                ]);
            }
        }

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }





// Show the form to edit a department
    // Show the form to edit a department
    // Controller method to show edit department modal
    public function editDepartment($id)
    {
        $department = Department::with('users', 'items')->findOrFail($id);
        $doctors = User::where('user_type', 'Doctor')->get();
        $items = Item::all();

        // Pass 'department' instead of 'departments'
        return view('admin.departments.index', compact('department', 'doctors', 'items'));
    }


// Update the details of a department
    public function updateDepartment(Request $request, $id)
    {
        $validatedData = $request->validate([
            'department_name' => 'required|string|max:100',
            'edit_doctors' => 'nullable|array',
            'edit_doctors.*' => 'exists:users,id',
            'edit_items' => 'nullable|array',
            'edit_items.*' => 'exists:items,id',
        ]);

        $department = Department::findOrFail($id);

        // Update department name
        $department->update(['department_name' => $validatedData['department_name']]);

        // Update associated users (doctors)
        if (isset($validatedData['edit_doctors'])) {
            foreach ($validatedData['edit_doctors'] as $doctorId) {
                $user = User::findOrFail($doctorId);

                // Check if the doctor is already associated with this department
                if (!$department->users->contains($user)) {
                    $department->users()->save($user);
                }
            }
        }

        // Update associated items
        if (isset($validatedData['edit_items'])) {
            foreach ($validatedData['edit_items'] as $itemId) {
                $item = Item::findOrFail($itemId);

                // Check if the item is already associated with this department
                if (!$department->items->contains($item)) {
                    $department->items()->save($item);
                }
            }
        }

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }
    // Delete a department
    public function deleteDepartment($id)
    {
        $department = Department::findOrFail($id);

        // Detach all items associated with this department
        $department->items()->detach();

        // Delete the department itself
        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully.');
    }




    //GESTIONSHIFTS

    // Show all shifts

    public function manageShifts()
    {
        $shifts = Shift::with(['doctors', 'employees'])->get();
        $doctors = User::where('user_type', 'Doctor')->get();
        $employees = User::whereIn('user_type', ['Nurse', 'Receptionist', 'SupportStaff'])->get();

        return view('admin.shifts.index', compact('shifts', 'doctors', 'employees'));
    }

    public function storeShift(Request $request)
    {
        $validatedData = $request->validate([
            'shift_name' => 'required|string|max:50',
            'start_datetime' => 'nullable|date_format:Y-m-d\TH:i',
            'end_datetime' => 'nullable|date_format:Y-m-d\TH:i',
            'doctor_ids' => 'nullable|array',
            'doctor_ids.*' => 'exists:users,id',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:users,id',
        ]);

        $shift = Shift::create([
            'shift_name' => $validatedData['shift_name'],
            'start_datetime' => isset($validatedData['start_datetime']) ? Carbon::parse($validatedData['start_datetime']) : null,
            'end_datetime' => isset($validatedData['end_datetime']) ? Carbon::parse($validatedData['end_datetime']) : null,
        ]);

        if (isset($validatedData['doctor_ids'])) {
            $shift->users()->attach($validatedData['doctor_ids']);
        }

        if (isset($validatedData['employee_ids'])) {
            $shift->users()->attach($validatedData['employee_ids']);
        }

        return redirect()->route('admin.shifts.index')->with('success', 'Shift created successfully.');
    }

    public function updateShift(Request $request, $id)
    {
        $validatedData = $request->validate([
            'shift_name' => 'required|string|max:50',
            'start_datetime' => 'nullable|date_format:Y-m-d\TH:i',
            'end_datetime' => 'nullable|date_format:Y-m-d\TH:i',
            'doctor_ids' => 'nullable|array',
            'doctor_ids.*' => 'exists:users,id',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:users,id',
        ]);

        $shift = Shift::findOrFail($id);
        $shift->update([
            'shift_name' => $validatedData['shift_name'],
            'start_datetime' => isset($validatedData['start_datetime']) ? Carbon::parse($validatedData['start_datetime']) : null,
            'end_datetime' => isset($validatedData['end_datetime']) ? Carbon::parse($validatedData['end_datetime']) : null,
        ]);

        $syncData = [];
        if (isset($validatedData['doctor_ids'])) {
            $syncData = array_merge($syncData, $validatedData['doctor_ids']);
        }
        if (isset($validatedData['employee_ids'])) {
            $syncData = array_merge($syncData, $validatedData['employee_ids']);
        }
        $shift->users()->sync($syncData);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift updated successfully.');
    }

    public function deleteShift($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $shift->users()->detach(); // Detach all users from the shift
            $shift->delete();

            return redirect()->route('admin.shifts.index')->with('success', 'Shift deleted successfully.');
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            return redirect()->route('admin.shifts.index')->with('error', 'Failed to delete shift.');
        }
    }



    public function manageItems()
    {
        $items = Item::all();
        return view('admin.items.index', compact('items'));
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'dosage' => 'nullable|string|max:255',
        ]);

        Item::create($request->all());

        return redirect()->route('admin.items.index')
            ->with('success', 'Item created successfully.');
    }

    public function updateItem(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'dosage' => 'nullable|string|max:255',
        ]);

        $item->update($request->all());

        return redirect()->route('admin.items.index')
            ->with('success', 'Item updated successfully.');
    }

    public function deleteItem(Item $item)
    {
        $item->delete();

        return redirect()->route('admin.items.index')
            ->with('success', 'Item deleted successfully.');
    }
}




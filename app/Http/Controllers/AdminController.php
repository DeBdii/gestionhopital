<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use App\Models\Item;
use App\Models\Appointment;
use App\Models\Shift;
use App\Models\Patient;
use App\Models\DepartmentItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{


    public function index()
    {

    }

    public function create()
    {

    }


    public function store(Request $request)
    {

    }


    public function show(string $id)
    {

    }


    public function edit(string $id)
    {

    }


    public function update(Request $request, string $id)
    {

    }


    public function destroy(string $id)
    {

    }




    public function manageDoctors(DoctorController $doctorController)
    {

        $doctors = User::where('user_type', 'Doctor')->get();

        return view('admin.doctor_management', compact('doctors'));
    }
    public function createDoctor(DoctorController $doctorController)
    {

        return $doctorController->create();
    }
    public function storeDoctor(Request $request, DoctorController $doctorController)
    {

        return $doctorController->store($request);
    }
    public function editDoctor($id, DoctorController $doctorController)
    {

        return $doctorController->edit($id);
    }
    public function updateDoctor(Request $request, $id, DoctorController $doctorController)
    {

        return $doctorController->update($request, $id);
    }
    public function deleteDoctor($id, DoctorController $doctorController)
    {

        return $doctorController->destroy($id);
    }






    public function manageStaff(StaffController $staffController)
    {

        $staffMembers = $staffController->index();
        return view('admin.staff_management', compact('staffMembers'));
    }

    public function createStaff(StaffController $staffController)
    {
        return $staffController->create();
    }

    public function storeStaff(Request $request, StaffController $staffController)
    {
        return $staffController->store($request);
    }

    public function editStaff($id, StaffController $staffController)
    {

        return $staffController->edit($id);
    }

    public function updateStaff(Request $request, $id, StaffController $staffController)
    {

        return $staffController->update($request, $id);
    }

    public function deleteStaff($id, StaffController $staffController)
    {

        return $staffController->destroy($id);
    }


    public function manageDepartments()
    {
        $departments = Department::with('users', 'items')->get();
        $doctors = User::where('user_type', 'Doctor')->whereDoesntHave('department')->get();
        $items = Item::all();

        return view('admin.departments.index', compact('departments', 'doctors', 'items'));
    }


    public function storeDepartment(Request $request)
    {
        $validatedData = $request->validate([
            'department_name' => 'required|string|max:100',
            'doctors' => 'nullable|array',
            'doctors.*' => 'exists:users,id',
            'items' => 'nullable|array',
            'items.*' => 'exists:items,id',
        ]);


        $department = Department::create(['department_name' => $validatedData['department_name']]);

        if (isset($validatedData['doctors'])) {
            foreach ($validatedData['doctors'] as $doctorId) {
                $doctor = User::find($doctorId);
                $doctor->department_id = $department->id;
                $doctor->save();
            }
        }

        if (isset($validatedData['items'])) {
            foreach ($validatedData['items'] as $itemId) {
                $item = Item::find($itemId);
                if ($item) {
                    DB::table('department_items')->insert([
                        'department_id' => $department->id,
                        'item_id' => $itemId,
                        'quantity' => $item->quantity,
                    ]);
                }
            }
        }

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }





    public function editDepartment($id)
    {
        $department = Department::with('users', 'items')->findOrFail($id);
        $doctors = User::where('user_type', 'Doctor')->get();
        $items = Item::all();
        return view('admin.departments.index', compact('department', 'doctors', 'items'));
    }



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

        $department->update(['department_name' => $validatedData['department_name']]);


        if (isset($validatedData['edit_doctors'])) {

            foreach ($validatedData['edit_doctors'] as $userId) {
                $user = User::findOrFail($userId);
                $user->department_id = $department->id;
                $user->save();
            }
        }

        if (isset($validatedData['edit_items'])) {
            $department->items()->sync($validatedData['edit_items']);
        } else {

            $department->items()->detach();
        }

        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully.');
    }


    public function deleteDepartment($id)
    {
        $department = Department::findOrFail($id);


        $department->items()->detach();


        $department->delete();

        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully.');
    }





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
            $shift->users()->detach();

            return redirect()->route('admin.shifts.index')->with('success', 'Shift deleted successfully.');
        } catch (\Exception $e) {

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
    public function dashboard()
    {
        $today = now()->format('Y-m-d');
        $availableDoctorsCount = Department::withCount(['users' => function ($query) use ($today) {
            $query->where('user_type', 'Doctor')
                ->whereHas('shifts', function ($query) use ($today) {
                    $query->whereDate('start_datetime', '<=', $today)
                        ->whereDate('end_datetime', '>=', $today);
                });
        }])->get();


        $itemsQuantity = Item::all();


        $appointmentsCount = Appointment::whereDate('appointment_date', '>=', $today)->count();


        $patientsCount = Patient::count();


        $todayAppointmentsCount = Appointment::whereDate('appointment_date', $today)->count();

        $receptionistsCount = User::where('user_type', 'Receptionist')
            ->whereHas('shifts', function ($query) use ($today) {
                $query->whereDate('start_datetime', '<=', $today)
                    ->whereDate('end_datetime', '>=', $today);
            })
            ->count();


        $nursesCount = User::where('user_type', 'Nurse')
            ->whereHas('shifts', function ($query) use ($today) {
                $query->whereDate('start_datetime', '<=', $today)
                    ->whereDate('end_datetime', '>=', $today);
            })
            ->count();


        $supportStaffCount = User::where('user_type', 'SupportStaff')
            ->whereHas('shifts', function ($query) use ($today) {
                $query->whereDate('start_datetime', '<=', $today)
                    ->whereDate('end_datetime', '>=', $today);
            })
            ->count();


        return view('admin.dashboard', compact('availableDoctorsCount', 'itemsQuantity', 'appointmentsCount', 'patientsCount', 'todayAppointmentsCount','receptionistsCount', 'nursesCount', 'supportStaffCount'));
    }
}




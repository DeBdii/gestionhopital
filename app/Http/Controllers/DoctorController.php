<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\Item;
use App\Models\Appointment;
use App\Models\DepartmentItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MedicalRecord;

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
        return view('admin.doctors.edit', compact('doctor'));
    }

    // Update (Update the details of a specific doctor)
    public function update(Request $request, $id)
    {
        $doctor = User::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|string|confirmed',
        'specialty' => 'nullable|string|max:255',
        'salary' => 'required|numeric',
    ]);

    if ($request->filled('password')) {
        $validated['password'] = bcrypt($validated['password']);
    } else {
        unset($validated['password']);
    }

    $doctor->update($validated);

    return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully.');
    }


    public function destroy($id)
    {
        $doctor = User::findOrFail($id);
        $doctor->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully.');
    }


    public function showCalender()
    {
        $user = Auth::user();


        $shifts = Shift::with('users')->get();


        $userShifts = $user->shifts->pluck('id')->toArray();


        $appointments = Appointment::with(['doctor', 'patient'])
            ->where('doctor_id', $user->id)
            ->get();

        return view('doctor.schedule.index', compact('shifts', 'userShifts', 'appointments'));
    }



    public function managePatients()
    {
        $doctorId = Auth::id();
        $patients = Patient::all();

        $patientt = Patient::whereHas('appointments', function ($query) use ($doctorId) {
        $query->where('doctor_id', $doctorId);
         })->get();
        return view('doctor.patients', compact('patients','patientt'));
    }


    public function storePatients(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        // Create new patient record
        $patient = Patient::create($validatedData);

        // Optionally, return a response
        return redirect()->back()->with('success', 'Patient added successfully');
    }

    public function updatePatients(Request $request, $id)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        // Find the patient by ID
        $patient = Patient::findOrFail($id);

        // Update patient record
        $patient->update($validatedData);

        // Optionally, return a response
        return redirect()->back()->with('success', 'Patient updated successfully');
    }
    public function destroyPatients(Patient $patient)
    {
        // Ensure the authenticated user has permission to delete the patient (if needed)

        // Delete the patient
        $patient->delete();

        // Optionally, you can return a response or redirect to a specific route
        return redirect()->route('doctor.patients')
            ->with('success', 'Patient deleted successfully');
    }







    public function showPatientDetails($patientId)
    {
        $patient = Patient::with('medicalRecords')->findOrFail($patientId);

        return view('doctor.patientdetails', compact('patient'));
    }

    public function storeMedicalRecord(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnosis' => 'required|string|max:255',
            'treatment_history' => 'nullable|string',
            'test_results' => 'nullable|string',
            'prescriptions' => 'nullable|string',
        ]);

        MedicalRecord::create($request->all());

        return redirect()->route('doctor.patients.show', ['patient' => $request->patient_id])
            ->with('success', 'Medical record added successfully');
    }
    public function updateMedicalRecord(Request $request, MedicalRecord $medicalRecord)
    {
        $request->validate([
            'diagnosis' => 'required|string|max:255',
            'treatment_history' => 'nullable|string',
            'test_results' => 'nullable|string',
            'prescriptions' => 'nullable|string',
        ]);

        $medicalRecord->update($request->all());

        return redirect()->route('doctor.patients.show', ['patient' => $medicalRecord->patient_id])
            ->with('success', 'Medical record updated successfully.');
    }

    // Delete a medical record
    public function destroyMedicalRecord(MedicalRecord $medicalRecord)
    {
        $patientId = $medicalRecord->patient_id;
        $medicalRecord->delete();

        return redirect()->route('doctor.patients.show', ['patient' => $patientId])
            ->with('success', 'Medical record deleted successfully.');
    }

    public function showStock()
{

    $doctor = Auth::user();


    $items = $doctor->department->items;

    // Pass the data to the view
    return view('doctor.stock', [
        'doctor' => $doctor,
        'items' => $items,
    ]);
}

public function demandItem(Request $request)
{
    $request->validate([
        'item_id' => 'required|exists:items,id',
        'quantity' => 'required|integer|min:1',
    ]);

    try {
        DB::beginTransaction();

        $item = Item::findOrFail($request->item_id);


        $item->quantity -= $request->quantity;
        $item->save();


        $departmentItem = DepartmentItem::where('department_id', Auth::user()->department_id)
                                        ->where('item_id', $request->item_id)
                                        ->firstOrFail();

        $departmentItem->quantity -= $request->quantity;
        $departmentItem->save();

        DB::commit();

        return redirect()->back()->with('success', 'Quantity demanded successfully.');
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Error demanding quantity: ' . $e->getMessage());
    }
}


    public function dashboard()
    {
        // Define today's date
        $today = now()->format('Y-m-d');

        // Fetch the logged-in doctor
        $doctor = auth()->user();

        // Fetch today's appointments for the logged-in doctor
        $todayAppointments = $doctor->appointmentsAsDoctor()
            ->whereDate('appointment_date', $today)
            ->get();

        // Fetch overall number of patients the doctor has appointments with
        $patientsCount = $doctor->appointmentsAsDoctor()
            ->distinct('patient_id')
            ->count('patient_id');

        // Fetch department items and their quantities (only the department the doctor is related to)
        $departmentItems = $doctor->department->items;

        // Fetch shift details for the logged-in doctor
        $shift = $doctor->shifts()
            ->whereDate('start_datetime', '<=', $today)
            ->whereDate('end_datetime', '>=', $today)
            ->first();

        // Prepare shift start and end times
        $shiftStartTime = optional($shift)->start_datetime ? \Carbon\Carbon::parse($shift->start_datetime)->format('H:i') : 'N/A';
        $shiftEndTime = optional($shift)->end_datetime ? \Carbon\Carbon::parse($shift->end_datetime)->format('H:i') : 'N/A';

        // Return data to the view using compact
        return view('doctor.dashboard', compact('todayAppointments', 'patientsCount', 'departmentItems', 'shiftStartTime', 'shiftEndTime', 'doctor'));
    }








}

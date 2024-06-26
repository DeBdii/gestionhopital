<?php

namespace App\Http\Controllers;

use App\Models\Patient; // Ensure this is correctly pointing to the Patient model

use Illuminate\Http\Request;

use App\Models\MedicalRecord;

use App\Models\Appointment;

use App\Models\User;

use App\Models\Shift;
use App\Models\Department;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ReceptionistController extends Controller
{
    public function managePatients()
    {
        $patients = Patient::all(); // Example: Fetching all patients
        return view('receptionist.patients', compact('patients'));
    }


    public function store(Request $request)
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

    public function update(Request $request, $id)
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
    public function destroy(Patient $patient)
    {
        // Ensure the authenticated user has permission to delete the patient (if needed)

        // Delete the patient
        $patient->delete();

        // Optionally, you can return a response or redirect to a specific route
        return redirect()->route('receptionist.patients')
            ->with('success', 'Patient deleted successfully');
    }







    public function showPatientDetails($patientId)
    {
        $patient = Patient::with('medicalRecords')->findOrFail($patientId);

        return view('receptionist.patientdetails', compact('patient'));
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

        return redirect()->route('receptionist.patients.show', ['patient' => $request->patient_id])
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

        return redirect()->route('receptionist.patients.show', ['patient' => $medicalRecord->patient_id])
            ->with('success', 'Medical record updated successfully.');
    }

    // Delete a medical record
    public function destroyMedicalRecord(MedicalRecord $medicalRecord)
    {
        $patientId = $medicalRecord->patient_id;
        $medicalRecord->delete();

        return redirect()->route('receptionist.patients.show', ['patient' => $patientId])
            ->with('success', 'Medical record deleted successfully.');
    }








    public function manageAppointments()
    {
        $patients = Patient::with('appointments')->get();

        $doctors = User::where('user_type', 'Doctor')
            ->with(['shifts', 'appointmentsAsDoctor'])
            ->get();

        return view('receptionist.appointments', [
            'patients' => $patients,
            'doctors' => $doctors,
        ]);
    }



    public function displayAppointments($doctor)
    {
        // Find the doctor by ID
        $doctor = User::findOrFail($doctor);
        $shifts = Shift::with('users')->get();
        // Retrieve appointments and shifts where the doctor is associated
        $appointments = Appointment::with(['doctor', 'patient'])
            ->where('doctor_id', $doctor->id)
            ->get();
        $userShifts = $doctor->shifts->pluck('id')->toArray();

        // Return appointments and shifts to be displayed
        return view('receptionist.doctorcalendar', compact('shifts', 'userShifts', 'appointments','doctor'));

    }

    public function manageItems()
{
    $items = Item::all();
    return view('receptionist.stock', compact('items'));
}






    public function dashboard()
    {
        // Define today's date
        $today = now()->format('Y-m-d');

        // Fetch count of doctors per department with shifts including today
        $availableDoctorsCount = Department::withCount(['users' => function ($query) use ($today) {
            $query->where('user_type', 'Doctor')
                ->whereHas('shifts', function ($query) use ($today) {
                    $query->whereDate('start_datetime', '<=', $today)
                        ->whereDate('end_datetime', '>=', $today);
                });
        }])->get();

        // Fetch items with their quantities
        $itemsQuantity = Item::all();

        // Fetch total appointments count
        $appointmentsCount = Appointment::count();

        // Fetch total patients count
        $patientsCount = Patient::count();

        // Return data to the view
        return view('receptionist.dashboard', compact('availableDoctorsCount', 'itemsQuantity', 'appointmentsCount', 'patientsCount'));
    }















}

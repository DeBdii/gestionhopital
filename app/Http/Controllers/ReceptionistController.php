<?php

namespace App\Http\Controllers;

use App\Models\Patient;
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
        $patients = Patient::all();
        return view('receptionist.patients', compact('patients'));
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);


        $patient = Patient::create($validatedData);


        return redirect()->back()->with('success', 'Patient added successfully');
    }

    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);
        $patient = Patient::findOrFail($id);

        $patient->update($validatedData);

        return redirect()->back()->with('success', 'Patient updated successfully');
    }
    public function destroy(Patient $patient)
    {
        $patient->delete();


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


    public function storeAppointment(Request $request)
    {

        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
        ]);


        $appointmentDateTime = $request->input('appointment_date') . ' ' . $request->input('appointment_time');


        $appointment = new Appointment();
        $appointment->doctor_id = $request->input('doctor_id');
        $appointment->patient_id = $request->input('patient_id');
        $appointment->appointment_date = $appointmentDateTime;
        $appointment->save();


        return redirect()->route('receptionist.appointments')->with('success', 'Appointment successfully created.');
    }

    public function displayAppointments($doctor)
    {
        $doctor = User::findOrFail($doctor);
        $shifts = Shift::with('users')->get();
        $appointments = Appointment::with(['doctor', 'patient'])
            ->where('doctor_id', $doctor->id)
            ->get();
        $userShifts = $doctor->shifts->pluck('id')->toArray();


        return view('receptionist.doctorcalendar', compact('shifts', 'userShifts', 'appointments','doctor'));

    }

    public function manageItems()
    {
        $items = Item::all();
        return view('receptionist.stock', compact('items'));
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

        return view('receptionist.dashboard', compact('availableDoctorsCount', 'itemsQuantity', 'appointmentsCount', 'patientsCount', 'todayAppointmentsCount'));
    }

}

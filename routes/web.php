<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceptionistController;


// Home route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Admin specific routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('', function () {
        return view('admin.dashboard');
    })->name('admin');

    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestion des Docteurs
    Route::get('doctors', [AdminController::class, 'manageDoctors'])->name('doctors.index');
    Route::get('doctors/create', [AdminController::class, 'createDoctor'])->name('doctors.create');
    Route::post('doctors', [AdminController::class, 'storeDoctor'])->name('doctors.store');
    Route::get('doctors/{id}/edit', [AdminController::class, 'editDoctor'])->name('doctors.edit');
    Route::put('doctors/{id}', [AdminController::class, 'updateDoctor'])->name('doctors.update');
    Route::delete('doctors/{id}', [AdminController::class, 'deleteDoctor'])->name('doctors.destroy');

    // Gestion des Staff
    Route::get('staff', [AdminController::class, 'manageStaff'])->name('staff.index');
    Route::get('staff/create', [AdminController::class, 'createStaff'])->name('staff.create');
    Route::post('staff', [AdminController::class, 'storeStaff'])->name('staff.store');
    Route::get('staff/{id}/edit', [AdminController::class, 'editStaff'])->name('staff.edit');
    Route::put('staff/{id}', [AdminController::class, 'updateStaff'])->name('staff.update');
    Route::delete('staff/{id}', [AdminController::class, 'deleteStaff'])->name('staff.destroy');

    // Gestion des Départements
    Route::get('departments', [AdminController::class, 'manageDepartments'])->name('departments.index');
    Route::get('departments/create', [AdminController::class, 'createDepartment'])->name('departments.create');
    Route::post('departments', [AdminController::class, 'storeDepartment'])->name('departments.store');
    Route::get('departments/{id}/edit', [AdminController::class, 'editDepartment'])->name('departments.edit');
    Route::put('departments/{id}', [AdminController::class, 'updateDepartment'])->name('departments.update');
    Route::delete('departments/{id}', [AdminController::class, 'deleteDepartment'])->name('departments.destroy');

    // Gestion des Shifts
    Route::get('shifts', [AdminController::class, 'manageShifts'])->name('shifts.index');
    Route::post('shifts', [AdminController::class, 'storeShift'])->name('shifts.store');
    Route::get('shifts/{id}/edit', [AdminController::class, 'editShift'])->name('shifts.edit');
    Route::put('shifts/{id}', [AdminController::class, 'updateShift'])->name('shifts.update');
    Route::delete('shifts/{id}', [AdminController::class, 'deleteShift'])->name('shifts.destroy');

    // Gestion de Stock
    Route::get('items', [AdminController::class, 'manageItems'])->name('items.index');
    Route::post('items', [AdminController::class, 'storeItem'])->name('items.store');
    Route::put('items/{item}', [AdminController::class, 'updateItem'])->name('items.update');
    Route::delete('items/{item}', [AdminController::class, 'deleteItem'])->name('items.destroy');



});

// Doctor specific routes
Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'doctor'])->group(function () {


    Route::get('', function () {
        return view('doctor.dashboard');
    })->name('doctor');


    Route::get('dashboard', function () {
        return view('doctor.dashboard');
    })->name('dashboard');


    Route::get('patients', [DoctorController::class, 'managePatients'])->name('patients');
    Route::post('patients', [DoctorController::class, 'storePatients'])->name('patients.store');
    Route::put('patients/{patient}', [DoctorController::class, 'updatePatients'])->name('patients.update');
    Route::delete('patients/{patient}', [DoctorController::class, 'destroyPatients'])->name('patients.destroy');
    // Other doctor routes
    Route::get('patients/{patient}', [DoctorController::class, 'showPatientDetails'])->name('patients.show');
    Route::post('medical-records/store', [DoctorController::class, 'storeMedicalRecord'])->name('medicalrecords.store');
    Route::put('medical-records/{medicalRecord}', [DoctorController::class, 'updateMedicalRecord'])->name('medicalrecords.update');
    Route::delete('medicalrecords/{medicalRecord}', [DoctorController::class, 'destroyMedicalRecord'])->name('medicalrecords.destroy');


    Route::get('/schedule', [DoctorController::class, 'showCalender'])->name('schedule.index');
    Route::get('/stock',[DoctorController::class, 'showStock'] )->name('stock');
    Route::post('/demand-item', [DoctorController::class, 'demandItem'])->name('demand.item');


});




Route::prefix('receptionist')->name('receptionist.')->middleware(['auth', 'receptionist'])->group(function () {
    Route::get('dashboard', [ReceptionistController::class, 'dashboard'])->name('dashboard');
    // Other routes...





    Route::post('appointments/store', [ReceptionistController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('appointments', [ReceptionistController::class, 'manageAppointments'])->name('appointments');
    Route::get('doctors/{doctorId}/calendar', [ReceptionistController::class, 'displayAppointments'])->name('doctors.calendar');


    Route::get('stock', [ReceptionistController::class, 'manageItems'])->name('stock');
    Route::get('appointmentss', [ReceptionistController::class, 'getAppointments']);

    Route::get('patients', [ReceptionistController::class, 'managePatients'])->name('patients');
    Route::post('patients', [ReceptionistController::class, 'store'])->name('patients.store');
    Route::put('patients/{patient}', [ReceptionistController::class, 'update'])->name('patients.update');
    Route::delete('patients/{patient}', [ReceptionistController::class, 'destroy'])->name('patients.destroy');


    Route::get('patients/{patient}', [ReceptionistController::class, 'showPatientDetails'])->name('patients.show');
    Route::post('medical-records/store', [ReceptionistController::class, 'storeMedicalRecord'])->name('medicalrecords.store');
    Route::put('medical-records/{medicalRecord}', [ReceptionistController::class, 'updateMedicalRecord'])->name('medicalrecords.update');
    Route::delete('/receptionist/medicalrecords/{medicalRecord}', [ReceptionistController::class, 'destroyMedicalRecord'])->name('medicalrecords.destroy');

});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


// Include the default auth routes
require __DIR__.'/auth.php';

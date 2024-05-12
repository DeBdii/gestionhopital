<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});






                                            //ADMIN ROUTES//

//GESTIONDESDOCTORS

Route::get('/admin/doctor', [AdminController::class, 'manageDoctors'])->name('admin.doctors.index');

// Route for showing the form to create a new doctor
Route::get('/admin/doctors/create', [AdminController::class, 'createDoctor'])->name('admin.doctors.create');

// Route for storing a newly created doctor
Route::post('/admin/doctors', [AdminController::class, 'storeDoctor'])->name('admin.doctors.store');

// Route for showing the form to edit a doctor
Route::get('/admin/doctors/{id}/edit', [AdminController::class, 'editDoctor'])->name('admin.doctors.edit');

// Route for updating the details of a doctor
Route::put('/admin/doctors/{id}', [AdminController::class, 'updateDoctor'])->name('admin.doctors.update');

// Route for deleting a doctor
Route::delete('/admin/doctors/{id}', [AdminController::class, 'deleteDoctor'])->name('admin.doctors.destroy');



require __DIR__.'/auth.php';

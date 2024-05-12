<?php

namespace App\Http\Controllers;
use App\Models\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DoctorController;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

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

}

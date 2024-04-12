<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;

class DoctorProfileDutTOPatientController extends Controller
{
    public function __construct() {
        $this->middleware('permission:show_doctor_profile')->only(['index', 'show']);
    }
    public function index()
    {
        if (Doctor::exists()) {
            $doctors = Doctor::with('availabilities')->paginate();
            return $this->paginateResponse(DoctorResource::collection($doctors));
        }
        return $this->errorResponse('data not found');
    }
    public function show($id)
    {
        $doctor = Doctor::with('availabilities')->find($id);
        if ($doctor) {
            return $this->okResponse('data fetched successfully',DoctorResource::make($doctor));
        }
        return $this->errorResponse('data not found');
    }
}

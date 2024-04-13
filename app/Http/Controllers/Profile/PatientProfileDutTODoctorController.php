<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientProfileDutTODoctorController extends Controller
{
    public function __construct() {
        $this->middleware('permission:show_patient_profile')->only(['index', 'show']);
    }
    public function index()
    {
        if (Patient::exists()) {
            $patients = Patient::paginate();
            return $this->paginateResponse(PatientResource::collection($patients));
        }
        return $this->errorResponse('data not found');
    }
    public function show($id)
    {
        $patient = Patient::find($id);
        if ($patient) {
            return $this->okResponse('data fetched successfully',PatientResource::make($patient));
        }
        return $this->errorResponse('data not found');
    }
}

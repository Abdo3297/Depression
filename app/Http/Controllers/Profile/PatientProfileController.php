<?php

namespace App\Http\Controllers\Profile;

use App\Models\Patient;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\PatientResource;
use App\Http\Requests\Profile\DeletePatientProfileRequest;
use App\Http\Requests\Profile\UpdatePatientProfileRequest;

class PatientProfileController extends Controller
{
    public function showProfile()
    {
        // get the authenticated user
        $patient = auth('patient_api')->user();
        if ($patient) {
            // return response
            return $this->okResponse('show profile successfully', PatientResource::make($patient));
        }
    }
    public function updateProfile(UpdatePatientProfileRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // get the authenticated user
        $patient = auth('patient_api')->user();
        if ($patient) {
            $patient = Patient::find($patient->id);
            // update data
            $patient->update($data);
            // handle image (clear old then create new)
            if ($request->hasFile('image')) {
                $patient->clearMediaCollection('patient_profile');
                $patient->addMediaFromRequest('image')->toMediaCollection('patient_profile');
            }
            // return response
            return $this->okResponse('update profile successfully', PatientResource::make($patient));
        }
    }
    public function deleteProfile(DeletePatientProfileRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // get the authenticated user
        $patient = auth('patient_api')->user();
        if ($patient) {
            $patient = Patient::find($patient->id);
            // compare password within data base to check user
            if (!Hash::check($data['password'], $patient->password)) {
                return $this->errorResponse('invalid password');
            }
            // delete token
            $patient->tokens()->delete();
            // delete user
            $patient->delete();
            // delete image
            $patient->clearMediaCollection('patient_profile');
            // return response
            return $this->okResponse('delete profile successfully',[]);
        }
    }
}

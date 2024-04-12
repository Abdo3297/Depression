<?php

namespace App\Http\Controllers\Profile;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\DoctorResource;
use App\Http\Requests\Profile\DeleteDoctorProfileRequest;
use App\Http\Requests\Profile\UpdateDoctorProfileRequest;

class DoctorProfileController extends Controller
{
    public function showProfile()
    {
        // get the authenticated user
        $patient = auth('doctor_api')->user();
        if ($patient) {
            // return response
            return $this->okResponse('show profile successfully', DoctorResource::make($patient));
        }
    }
    public function updateProfile(UpdateDoctorProfileRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // get the authenticated user
        $patient = auth('doctor_api')->user();
        if ($patient) {
            $patient = Doctor::find($patient->id);
            // update data
            $patient->update($data);
            // handle image (clear old then create new)
            if ($request->hasFile('image')) {
                $patient->clearMediaCollection('doctor_profile');
                $patient->addMediaFromRequest('image')->toMediaCollection('doctor_profile');
            }
            // return response
            return $this->okResponse('update profile successfully', DoctorResource::make($patient));
        }
    }
    public function deleteProfile(DeleteDoctorProfileRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // get the authenticated user
        $patient = auth('doctor_api')->user();
        if ($patient) {
            $patient = Doctor::find($patient->id);
            // compare password within data base to check user
            if (!Hash::check($data['password'], $patient->password)) {
                return $this->errorResponse('invalid password');
            }
            // delete token
            $patient->tokens()->delete();
            // delete user
            $patient->delete();
            // delete image
            $patient->clearMediaCollection('doctor_profile');
            // return response
            return $this->okResponse('delete profile successfully',[]);
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Models\Doctor;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\DoctorResource;
use App\Mail\Auth\DoctorForgetPasswordMail;
use App\Http\Requests\Auth\DoctorSignInRequest;
use App\Http\Requests\Auth\DoctorSignUpRequest;
use App\Http\Requests\Auth\CheckOTPDoctorRequest;
use App\Http\Requests\Auth\ResetPasswordDoctorRequest;
use App\Http\Requests\Auth\ChangePasswordDoctorRequest;
use App\Http\Requests\Auth\ForgetPasswordDoctorRequest;

class DoctorAuthController extends Controller
{
    public function signup(DoctorSignUpRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // create record
        $patient = Doctor::create($data);

        // assign role to user
        $patient_role = Role::where('name', 'doctor')->first();
        if ($patient_role) {
            $patient->assignRole($patient_role);
        }
        // create token
        $token = $patient->createToken("token")->plainTextToken;
        // return response
        return $this->signResponse('created user successfully', DoctorResource::make($patient), $token,201);
    }

    public function signin(DoctorSignInRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // search about user by email
        $patient = Doctor::where('email', $data['email'])->first();
        // check email with given password
        if ($patient && Hash::check($data['password'], $patient->password)) {
            // delete old tokens
            $patient->tokens()->delete();
            // create new token
            $token = $patient->createToken("token")->plainTextToken;
            // return response
            return $this->signResponse('user login successfully', DoctorResource::make($patient), $token,200);
        }
        // return response
        return $this->errorResponse('invalid email or password');
    }

    public function signout()
    {
        // get the authenticated user
        $patient = auth('doctor_api')->user();
        if ($patient) {
            $patient = Doctor::find($patient->id);
            // delete old tokens
            $patient->tokens()->delete();
            // return response
            return $this->okResponse('user logged out', []);
        }
    }
    public function forgetPassword(ForgetPasswordDoctorRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // search about user by email
        $patient = Doctor::where('email', $data['email'])->first();
        // generate otp code
        $otp =  (new Otp)->generate($patient->email, config('depression_constant.NUMERIC'), config('depression_constant.LENGTH'), config('depression_constant.VALIDITY'))->token;
        // send otp to user
        Mail::to($patient->email)->send(new DoctorForgetPasswordMail($patient, $otp));
        // return response 
        return $this->okResponse('otp send successfully', []);
    }

    public function resendOtp(ForgetPasswordDoctorRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // search about user by email
        $patient = Doctor::where('email',$data['email'])->first();
        // generate otp code
        $otp =  (new Otp)->generate($patient->email, config('depression_constant.NUMERIC'), config('depression_constant.LENGTH'), config('depression_constant.VALIDITY'))->token;
        // send otp to user
        Mail::to($patient->email)->send(new DoctorForgetPasswordMail($patient,$otp));
        // return response
        return $this->okResponse('otp resend successfully', []);
    }

    public function checkOTP(CheckOTPDoctorRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // search about otp by email
        $otp = DB::table('otps')
            ->where('identifier', $data['email'])
            ->latest()
            ->first();
        // check valid otp
        if ( ($otp->token == $data['otp']) && $otp->valid ) {
            // return response
            return $this->okResponse('valid otp', []);
        }
        // return response
        return $this->errorResponse('not valid otp');
    }

    public function resetPassword(ResetPasswordDoctorRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // search about user by email
        $patient = Doctor::where('email', $data['email'])->first();
        // check for valid otp
        $otp2 = (new Otp)->validate($patient->email, $data['otp']);
        if (!$otp2->status) {
            // return response
            return $this->errorResponse('not valid otp');
        }
        // update password
        $patient->update([
            'password' => Hash::make($data['password'])
        ]);
        // delete old tokens
        $patient->tokens()->delete();
        // return response
        return $this->okResponse('password reset successfully',[]);
    }
    public function changePassword(ChangePasswordDoctorRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // get the authenticated user
        $patient = auth('doctor_api')->user();
        if($patient) {
            $patient = Doctor::find($patient->id);
            // check password
            if (!Hash::check($data['current_password'], $patient->password)) {
                // return response
                return $this->errorResponse('not valid password');
            }
            // update password
            $patient->update([
                'password' => Hash::make($data['new_password']),
            ]);
            // return response
            return $this->okResponse('password change successfully',[]);
        }
    }
}

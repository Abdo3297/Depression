<?php

namespace App\Http\Controllers\Auth;

use Ichtrojan\Otp\Otp;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordPatientRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\PatientResource;
use App\Mail\Auth\PatientForgetPasswordMail;
use App\Http\Requests\Auth\PatientSignInRequest;
use App\Http\Requests\Auth\PatientSignUpRequest;
use App\Http\Requests\Auth\CheckOTPatientRequest;
use App\Http\Requests\Auth\ForgetPasswordPatientRequest;
use App\Http\Requests\Auth\ResetPasswordPatientRequest;

class PatientAuthController extends Controller
{

    public function signup(PatientSignUpRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // create record
        $patient = Patient::create($data);

        // assign role to user
        $patient_role = Role::where('name', 'patient')->first();
        if ($patient_role) {
            $patient->assignRole($patient_role);
        }
        // create token
        $token = $patient->createToken("token")->plainTextToken;
        // return response
        return $this->signResponse('created user successfully', PatientResource::make($patient), $token,201);
    }

    public function signin(PatientSignInRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // search about user by email
        $patient = Patient::where('email', $data['email'])->first();
        // check email with given password
        if ($patient && Hash::check($data['password'], $patient->password)) {
            // delete old tokens
            $patient->tokens()->delete();
            // create new token
            $token = $patient->createToken("token")->plainTextToken;
            // return response
            return $this->signResponse('user login successfully', PatientResource::make($patient), $token,200);
        }
        // return response
        return $this->errorResponse('invalid email or password');
    }

    public function signout()
    {
        // get the authenticated user
        $patient = auth('patient_api')->user();
        if ($patient) {
            $patient = Patient::find($patient->id);
            // delete old tokens
            $patient->tokens()->delete();
            // return response
            return $this->okResponse('user logged out', []);
        }
    }

    public function forgetPassword(ForgetPasswordPatientRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // search about user by email
        $patient = Patient::where('email', $data['email'])->first();
        // generate otp code
        $otp =  (new Otp)->generate($patient->email, config('depression_constant.NUMERIC'), config('depression_constant.LENGTH'), config('depression_constant.VALIDITY'))->token;
        // send otp to user
        Mail::to($patient->email)->send(new PatientForgetPasswordMail($patient, $otp));
        // return response 
        return $this->okResponse('otp send successfully', []);
    }

    public function resendOtp(ForgetPasswordPatientRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // search about user by email
        $patient = Patient::where('email',$data['email'])->first();
        // generate otp code
        $otp =  (new Otp)->generate($patient->email, config('depression_constant.NUMERIC'), config('depression_constant.LENGTH'), config('depression_constant.VALIDITY'))->token;
        // send otp to user
        Mail::to($patient->email)->send(new PatientForgetPasswordMail($patient,$otp));
        // return response
        return $this->okResponse('otp resend successfully', []);
    }

    public function checkOTP(CheckOTPatientRequest $request)
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

    public function resetPassword(ResetPasswordPatientRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // search about user by email
        $patient = Patient::where('email', $data['email'])->first();
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
    public function changePassword(ChangePasswordPatientRequest $request)
    {
        // get validated data
        $data = $request->validated();
        // get the authenticated user
        $patient = auth('patient_api')->user();
        if($patient) {
            $patient = Patient::find($patient->id);
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

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\DoctorAuthController;
use App\Http\Controllers\Auth\PatientAuthController;
use App\Http\Controllers\Prediction\PredictionController;
use App\Http\Controllers\Profile\DoctorProfileController;
use App\Http\Controllers\Statistics\StatisticsController;
use App\Http\Controllers\Profile\PatientProfileController;
use App\Http\Controllers\Profile\DoctorProfileDutTOPatientController;
use App\Http\Controllers\Profile\PatientProfileDutTODoctorController;

Route::get('{type}/auth/google', [SocialiteController::class, 'redirectToGoogle'])->whereIn('type', ['patient', 'doctor']);
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

Route::prefix('patient')->group(function () {
    Route::post('sign-up', [PatientAuthController::class, 'signup']);
    Route::post('sign-in', [PatientAuthController::class, 'signin']);
    Route::post('forget-password', [PatientAuthController::class, 'forgetPassword']);
    Route::post('resend-otp', [PatientAuthController::class, 'resendOTP']);
    Route::post('check-otp', [PatientAuthController::class, 'checkOTP']);
    Route::post('reset-password', [PatientAuthController::class, 'resetPassword']);
});

Route::prefix('patient')->middleware(['auth:patient_api'])->group(function () {
    Route::post('sign-out', [PatientAuthController::class, 'signout']);
    Route::post('change-password', [PatientAuthController::class, 'changePassword']);
    Route::get('show-profile', [PatientProfileController::class, 'showProfile']);
    Route::post('update-profile', [PatientProfileController::class, 'updateProfile']);
    Route::delete('delete-profile', [PatientProfileController::class, 'deleteProfile']);
    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{id}', [PostController::class, 'show']);
    Route::get('show-doctors-profile', [DoctorProfileDutTOPatientController::class, 'index']);
    Route::get('show-doctors-profile/{id}', [DoctorProfileDutTOPatientController::class, 'show']);
    Route::post('survey-predict', [PredictionController::class, 'predict']);
});

Route::prefix('doctor')->group(function () {
    Route::post('sign-up', [DoctorAuthController::class, 'signup']);
    Route::post('sign-in', [DoctorAuthController::class, 'signin']);
    Route::post('forget-password', [DoctorAuthController::class, 'forgetPassword']);
    Route::post('resend-otp', [DoctorAuthController::class, 'resendOTP']);
    Route::post('check-otp', [DoctorAuthController::class, 'checkOTP']);
    Route::post('reset-password', [DoctorAuthController::class, 'resetPassword']);
});

Route::prefix('doctor')->middleware(['auth:doctor_api'])->group(function () {
    Route::post('sign-out', [DoctorAuthController::class, 'signout']);
    Route::post('change-password', [DoctorAuthController::class, 'changePassword']);
    Route::get('show-profile', [DoctorProfileController::class, 'showProfile']);
    Route::post('update-profile', [DoctorProfileController::class, 'updateProfile']);
    Route::delete('delete-profile', [DoctorProfileController::class, 'deleteProfile']);

    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{id}', [PostController::class, 'show']);
    Route::post('posts', [PostController::class, 'store']);
    Route::delete('posts/{id}', [PostController::class, 'destroy']);
    Route::post('posts/{id}', [PostController::class, 'update']);

    Route::get('show-patients-profile', [PatientProfileDutTODoctorController::class, 'index']);
    Route::get('show-patients-profile/{id}', [PatientProfileDutTODoctorController::class, 'show']);
    
    Route::get('statistics',[StatisticsController::class,'statistics']);
});

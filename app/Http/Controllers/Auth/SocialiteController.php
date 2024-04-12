<?php

namespace App\Http\Controllers\Auth;


use App\Models\Patient;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        $type = $request->route('type');
        session(['type' => $type]);
        return Socialite::driver(config('depression_constant.GoogleDriver'))->stateless()->redirect();
    }
    public function handleGoogleCallback(Request $request)
    {
        $type = session('type');
        $request->session()->forget('type');
        if ($type == 'patient') {
            return $this->Socialite(Patient::class,'patient','patient_profile');
        } else {
            return $this->Socialite(Doctor::class,'doctor','doctor_profile');
        }
        
    }
    private function Socialite($modelName,$roleName,$mediaCollection)
    {
        $socialiteUser = Socialite::driver(config('depression_constant.GoogleDriver'))->stateless()->user();

        $user = $modelName::updateOrCreate([
            'provider' => config('depression_constant.GoogleDriver'),
            'provider_id' => $socialiteUser->getId(),
        ], [
            'name' => $socialiteUser->getName(),
            'email' => $socialiteUser->getEmail(),
        ]);

        $sanctum_token = $user->createToken("token")->plainTextToken;

        $user_role = Role::where('name', $roleName)->first();
        if ($user_role) {
            $user->assignRole($user_role);
        }

        $user->addMediaFromUrl($socialiteUser->getAvatar())->toMediaCollection($mediaCollection);

        return $this->socialiteResponse(config('depression_constant.GoogleDriver'), $user->id, $socialiteUser->getName(), $socialiteUser->getEmail(), $socialiteUser->getAvatar(), $socialiteUser->token, $sanctum_token);
    }
}

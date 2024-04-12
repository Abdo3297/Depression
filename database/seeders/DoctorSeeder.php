<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctorPermissions = [
            'show_post',
            'store_post',
            'update_post',
            'delete_post',
            'show_patient_profile'
        ];
        foreach ($doctorPermissions as $permission) {
            Permission::Create([
                'name' => $permission,
                'guard_name' => 'doctor_api',
            ]);
        }
        $doctor_role = Role::updateOrCreate(['name' => 'doctor'], [
            'name' => 'doctor',
            'guard_name' => 'doctor_api',
        ]);
        $doctor_role->givePermissionTo($doctorPermissions);

        $doctor = Doctor::create([
            'name' => 'doctor',
            'email' => 'doctor@test.com',
            'password' => Hash::make('doctor@test.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ])->assignRole($doctor_role);
    }
}

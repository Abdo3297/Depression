<?php

namespace Database\Seeders;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patientPermissions = [
            'show_post',
            'show_doctor_profile'
        ];
        foreach ($patientPermissions as $permission) {
            Permission::Create([
                'name' => $permission,
                'guard_name' => 'patient_api',
            ]);
        }
        $patient_role = Role::updateOrCreate(['name' => 'patient'], [
            'name' => 'patient',
            'guard_name' => 'patient_api',
        ]);
        $patient_role->givePermissionTo($patientPermissions);

        $patient = Patient::create([
            'name' => 'patient',
            'email' => 'patient@test.com',
            'password' => Hash::make('patient@test.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ])->assignRole($patient_role);
    }
}

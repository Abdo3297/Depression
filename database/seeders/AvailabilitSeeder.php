<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Availability;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AvailabilitSeeder extends Seeder
{
    public function run(): void
    {
        $doctorIds = Doctor::pluck('id')->toArray();

        $chunkSize = 10;

        $totalRecords = 20;

        $data = [];

        for ($i = 0; $i < $totalRecords; $i++) {
            $data[] = [
                'doctor_id' => fake()->randomElement($doctorIds),
                'day' => fake()->randomElement(['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
                'from' => fake()->time('H'),
                'to' => fake()->time('H'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            if (count($data) === $chunkSize) {
                Availability::insert($data);
                $data = [];
            }
        }

        // Insert any remaining records
        if (!empty($data)) {
            Availability::insert($data);
        }
    }
}

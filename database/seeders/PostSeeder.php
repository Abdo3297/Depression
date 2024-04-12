<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $doctorIds = Doctor::pluck('id')->toArray();

        $chunkSize = 10;
        
        $totalRecords = 20;

        $data = [];
        
        for ($i = 0; $i < $totalRecords; $i++) {
            $data[] = [
                'text_body' => fake()->realText(),
                'doctor_id' => fake()->randomElement($doctorIds),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            if (count($data) === $chunkSize) {
                Post::insert($data);
                $data = [];
            }
        }

        // Insert any remaining records
        if (!empty($data)) {
            Post::insert($data);
        }
    }
}

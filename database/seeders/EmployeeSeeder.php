<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $companyIds = Company::pluck('id');

        for ($i = 0; $i < 40; $i++) {
            DB::table('employees')->insert([
                'name' => $faker->firstName . ' ' . $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'company_id' => $companyIds->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $company = Company::first();
        
        if ($company) {
            User::create([
                'name' => 'Company User',
                'email' => 'company@mail.com',
                'password' => Hash::make('password'),
                'role' => 'company',
                'company_id' => $company->id,
            ]);
        }
    }
}
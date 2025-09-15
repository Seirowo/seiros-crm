<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use App\Models\ActivityLog; 

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            $companyCount = Company::count();
            $employeeCount = Employee::count();
            $userCount = User::count();

            $recentActivities = ActivityLog::with('user')->latest()->limit(10)->get();
            
            return view('dashboard', compact('companyCount', 'employeeCount', 'userCount', 'recentActivities'));
        } 

        else {
            $company = Auth::user()->company;
            if ($company) {
                $employeeCount = $company->employees->count();
            } else {
                $employeeCount = 0;
            }

            return view('dashboard', compact('employeeCount'));
        }
    }
}
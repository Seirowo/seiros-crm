<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\ActivityLog;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $companyId = $request->input('company_id');

        if (Auth::user()->role === 'company') {
            $companyId = Auth::user()->company_id;
        }

        $query = Employee::query();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            })->orWhereHas('company', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        
        $employees = $query->with('company')->paginate(10);
        $companies = Company::all(); 

        return view('employees.index', compact('employees', 'companies'));
    }

    public function create(Request $request)
    {
        $companyId = $request->query('company_id');
        $companies = Company::all(); 
        $selectedCompany = null;
    
        if ($companyId) {
            $selectedCompany = Company::findOrFail($companyId);
        }

        return view('employees.create', [
            'companies' => $companies,
            'selectedCompany' => $selectedCompany,
        ]);
    }
    
    public function store(StoreEmployeeRequest $request)
    {
        $validatedData = $request->validated();
        
        if (Auth::user()->role === 'company') {
            $validatedData['company_id'] = Auth::user()->company_id;
        }

       $employee = Employee::create($validatedData);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'created',
            'description' => 'User ' . Auth::user()->name . ' created a new employee: ' . $employee->name,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully!');

    }

    public function edit(Employee $employee)
    {
        if (Auth::user()->role === 'company' && $employee->company_id !== Auth::user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        if (Auth::user()->role === 'admin') {
            $companies = Company::all();
            return view('employees.edit', compact('employee', 'companies'));
        }
        return view('employees.edit', compact('employee'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        if (Auth::user()->role === 'company' && $employee->company_id !== Auth::user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validated();
        
        if (Auth::user()->role === 'company') {
            unset($validatedData['company_id']);
        }

        $employee->update($validatedData);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'updated',
            'description' => 'User ' . Auth::user()->name . ' updated employee: ' . $employee->name,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy(Employee $employee)
    {
        if (Auth::user()->role === 'company' && $employee->company_id !== Auth::user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $employeeName = $employee->name;
        
        $employee->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'description' => 'User ' . Auth::user()->name . ' deleted employee: ' . $employeeName,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}

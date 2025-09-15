<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ActivityLog;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Company::query();

        if (Auth::user()->role === 'company') {
            $query->where('id', Auth::user()->company_id);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('website', 'like', '%' . $search . '%');
        }

        $companies = $query->paginate(10);

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|string', 
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
        ]);
    
        if ($request->logo) {
            $base64Image = $request->logo;
            $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
            $imageData = base64_decode($imageData);
            $filename = Str::uuid() . '.jpeg';

            $path = 'logos/' . $filename;
            Storage::disk('public')->put($path, $imageData);

            $validatedData['logo'] = $path;
        }
    
        $company = Company::create($validatedData);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'created',
            'description' => 'User ' . Auth::user()->name . ' created a new company: ' . $company->name,
        ]);
        
        return redirect()->route('companies.index')
                         ->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        if (Auth::user()->role === 'company' && $company->id !== Auth::user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company) 
    {
        if (Auth::user()->role === 'company' && $company->id !== Auth::user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'delete_logo' => 'boolean', 
        ]);

        if ($request->boolean('delete_logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $validatedData['logo'] = null; 
        } 

        elseif ($request->has('logo') && !empty($request->logo)) { 

            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            
            $base64Image = $request->logo;
            if (Str::startsWith($base64Image, 'data:image')) {
                 $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
            } else {
                $imageData = $base64Image; 
            }
            
            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                 return back()->withErrors(['logo' => 'Invalid base64 image data.'])->withInput();
            }

            $filename = Str::uuid() . '.jpeg';
            $path = 'logos/' . $filename; 
            Storage::disk('public')->put($path, $imageData);

            $validatedData['logo'] = $path;
        } 
       
        else {
            unset($validatedData['logo']); 
        }

        unset($validatedData['delete_logo']);

        $company->update($validatedData);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'updated',
            'description' => 'User ' . Auth::user()->name . ' updated company: ' . $company->name,
        ]);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully!');
    }

    public function destroy(Company $company)
    {
        if (Auth::user()->role === 'company' && $company->id !== Auth::user()->company_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'description' => 'User ' . Auth::user()->name . ' deleted company: ' . $company->name,
        ]);

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully!');
    }
}
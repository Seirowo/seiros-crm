<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (Auth::user()->role === 'admin')
                        <h3 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}!</h3>
                        <p class="mb-6">Here is a summary of all companies and employees in the system.</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Total Companies</h4>
                                <p class="text-4xl font-bold">{{ $companyCount }}</p>
                                <a href="{{ route('companies.index') }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Manage Companies
                                </a>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Total Employees</h4>
                                <p class="text-4xl font-bold">{{ $employeeCount }}</p>
                                <a href="{{ route('employees.index') }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Manage Employees
                                </a>
                            </div>
                            
                            <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Total Users</h4>
                                <p class="text-4xl font-bold">{{ $userCount }}</p>
                                <a href="{{ route('users.index') }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Manage Users
                                </a>
                            </div>

                        </div>
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow mt-6">
                            <h4 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-200">Recent Activities</h4>
                            <ul class="space-y-2">
                                @forelse ($recentActivities as $activity)
                                    <li class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p>{{ $activity->description }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">by {{ $activity->user->name ?? 'System' }} {{ $activity->created_at->diffForHumans() }}</p>
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-gray-500 dark:text-gray-400">No recent activities.</li>
                                @endforelse
                            </ul>
                        </div>
                    @else
                        <h3 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}!</h3>
                        <p class="mb-6">This is your company's private dashboard. You can only manage your own employees and company profile here.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">Total Employees at {{ Auth::user()->company->name ?? 'Your Company' }}</h4>
                                <p class="text-4xl font-bold">{{ $employeeCount }}</p>
                                <a href="{{ route('employees.index') }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Manage My Employees
                                </a>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                                @if (Auth::user()->company)
                                <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">My Company Profile</h4>
                                <p class="text-4xl font-bold">{{ Auth::user()->company->name ?? 'N/A' }}</p>
                                <a href="{{ route('companies.edit', Auth::user()->company_id) }}" class="mt-4 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Edit Company Profile
                                </a>
                                @else
                                <p>No company profile to edit.</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
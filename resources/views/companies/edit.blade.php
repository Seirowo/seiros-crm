<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Company') }}
        </h2>
    </x-slot>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Edit Company</h3>
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('companies.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md">
                                Back
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md">
                                Back
                            </a>
                        @endif
                    </div>
                    
                    <form action="{{ route('companies.update', $company->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="logo_original" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo</label>
                            <div class="flex items-center mt-1">
                                <input type="file" name="logo_original" id="logo_original" accept="image/*" class="custom-file-input">
                                <label for="logo_original" class="custom-file-label">Pilih File</label>
                                <span id="file-name-display" class="file-name">No file chosen</span>
                            </div>
                            @error('logo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Logo</label>
                            <div class="w-32 h-32 rounded-lg border-2 border-gray-300 dark:border-gray-600 overflow-hidden shadow-sm flex items-center justify-center relative">
                                @if ($company->logo)
                                    <img id="logo-preview" class="w-full h-full object-cover" src="{{ asset('storage/' . $company->logo) }}" alt="Logo Preview">
                                    <button type="button" id="delete-logo-btn" class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 text-xs opacity-80 hover:opacity-100 transition-opacity">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @else
                                    <img id="logo-preview" class="w-full h-full object-cover hidden" src="" alt="Logo Preview">
                                    <span id="placeholder-icon" class="text-gray-400 dark:text-gray-500 text-5xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l1.5-1.5a1.125 1.125 0 011.5 0l1.5 1.5a1.125 1.125 0 001.5 0l1.5-1.5a1.125 1.125 0 011.5 0l1.5 1.5a1.125 1.125 0 001.5 0l1.5-1.5a1.125 1.125 0 011.5 0l1.5 1.5a1.125 1.125 0 001.5 0L21.75 12m-13.5 3l4.5-4.5m-4.5 0l4.5 4.5" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <input type="hidden" id="logo_cropped" name="logo">
                        <input type="hidden" id="delete_logo_flag" name="delete_logo" value="0">

                        <div class="mb-4">
                            <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                            <input type="url" name="website" id="website" value="{{ old('website', $company->website) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            @error('website')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $company->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="cropModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden z-50">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-2xl w-full">
            <h4 class="text-xl font-bold mb-4 dark:text-white">Crop Logo</h4>
            <div class="w-full h-80 overflow-hidden mb-4">
                <img id="image-box" class="block max-w-full h-auto mx-auto" src="">
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" id="cropCancelBtn" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">Batal</button>
                <button type="button" id="cropBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Crop</button>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        window.appData = {
            companyHasLogo: {{ $company->logo ? 'true' : 'false' }},
            companyLogoUrl: "{{ $company->logo ? asset('storage/' . $company->logo) : '' }}"
        };
    </script>
    <script src="{{ asset('js/comp-logic.js') }}"></script>
</x-app-layout>
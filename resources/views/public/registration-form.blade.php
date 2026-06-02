<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title }} - Student Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-black text-gray-900 mb-2">{{ $form->title }}</h1>
            @if($form->description)
                <p class="text-gray-700 text-lg">{{ $form->description }}</p>
            @endif
            <p class="text-sm text-red-600 font-semibold mt-2">Form closes: {{ $form->end_date->format('M d, Y h:i A') }}</p>
        </div>

        <!-- Notice Photo Section -->
        @if($form->notice_photo)
            <div class="mb-8 bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-blue-600 text-white px-6 py-3 font-bold text-lg">
                    📋 Important Notice
                </div>
                <div class="p-6">
                    <img src="{{ Storage::url($form->notice_photo) }}" alt="Notice Photo" class="w-full rounded-lg shadow-md mb-4">
                    <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t-2 border-gray-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-6 4h6m-6 4h6m3-11h2a2 2 0 012 2v10a2 2 0 01-2 2h-2"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-600">Starts</p>
                                <p class="font-bold text-gray-900">{{ $form->start_date->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-6 4h6m-6 4h6m3-11h2a2 2 0 012 2v10a2 2 0 01-2 2h-2"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-600">Ends</p>
                                <p class="font-bold text-gray-900">{{ $form->end_date->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Errors -->
        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <h3 class="text-red-800 font-bold mb-2">Please correct the following errors:</h3>
                <ul class="list-disc list-inside text-red-700 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <form action="{{ route('public.registration.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Student Information Section -->
                <div class="bg-gray-700 text-white px-6 py-3 font-bold text-lg">
                    Student Information
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                Student Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="student_name" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" value="{{ old('student_name') }}">
                            @error('student_name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                Student Number <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="student_number" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" value="{{ old('student_number') }}">
                            @error('student_number') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                Student ID No <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="student_id_no" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" value="{{ old('student_id_no') }}">
                            @error('student_id_no') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                Email <span class="text-red-600">*</span>
                            </label>
                            <input type="email" name="email" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" value="{{ old('email') }}">
                            @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                Department <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="department" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" placeholder="e.g., Computer Science" value="{{ old('department') }}">
                            @error('department') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                Semester <span class="text-red-600">*</span>
                            </label>
                            <select name="semester" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                <option value="">-- Select Semester --</option>
                                <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>2nd Semester</option>
                                <option value="3" {{ old('semester') == '3' ? 'selected' : '' }}>3rd Semester</option>
                                <option value="4" {{ old('semester') == '4' ? 'selected' : '' }}>4th Semester</option>
                                <option value="5" {{ old('semester') == '5' ? 'selected' : '' }}>5th Semester</option>
                                <option value="6" {{ old('semester') == '6' ? 'selected' : '' }}>6th Semester</option>
                                <option value="7" {{ old('semester') == '7' ? 'selected' : '' }}>7th Semester</option>
                                <option value="8" {{ old('semester') == '8' ? 'selected' : '' }}>8th Semester</option>
                            </select>
                            @error('semester') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="bg-gray-700 text-white px-6 py-3 font-bold text-lg">
                    Contact Information
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">
                            Phone Number <span class="text-red-600">*</span>
                        </label>
                        <input type="tel" name="phone_number" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" value="{{ old('phone_number') }}">
                        @error('phone_number') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Parent Information Section -->
                <div class="bg-gray-700 text-white px-6 py-3 font-bold text-lg">
                    Parent/Guardian Information
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                Parent Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="parent_name" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" value="{{ old('parent_name') }}">
                            @error('parent_name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                Parent Phone <span class="text-red-600">*</span>
                            </label>
                            <input type="tel" name="parent_phone" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" value="{{ old('parent_phone') }}">
                            @error('parent_phone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Document Upload Section -->
                <div class="bg-gray-700 text-white px-6 py-3 font-bold text-lg">
                    Document Upload
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">
                            Student ID Card Photo <span class="text-red-600">*</span>
                        </label>
                        <input type="file" name="id_card_photo" required accept="image/*" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-600 mt-2">Upload a clear photo of your student ID card (Max: 2MB)</p>
                        @error('id_card_photo') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">
                            Department Application Photos <span class="text-red-600">*</span>
                        </label>
                        <input type="file" name="application_photos[]" required accept="image/*" multiple class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-600 mt-2">Upload application documents/photos (Multiple files allowed, Max: 2MB each)</p>
                        @error('application_photos') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Terms -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <p class="text-xs text-gray-700 text-center">
                        <span class="text-red-600">* Required fields</span> must be filled out before submission.
                    </p>
                    <p class="text-xs text-gray-600 text-center mt-2">
                        By submitting this form, you confirm that all information provided is accurate and complete.
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="px-6 py-6 bg-white border-t border-gray-200">
                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition text-lg shadow-md">
                        Submit Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

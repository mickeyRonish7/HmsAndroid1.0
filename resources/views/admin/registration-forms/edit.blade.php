<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Edit: {{ $registrationForm->title }}</h2>
            <a href="{{ route('admin.registration-forms.index') }}" class="text-blue-600 hover:underline">← Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <form action="{{ route('admin.registration-forms.update', $registrationForm->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Form Title *</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-500 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900 transition" value="{{ old('title', $registrationForm->title) }}">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-500 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900 transition">{{ old('description', $registrationForm->description) }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Notice Photo</label>
                        @if($registrationForm->notice_photo)
                            <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <p class="text-xs text-gray-600 dark:text-gray-300 mb-2">Current Photo:</p>
                                <img src="{{ Storage::url($registrationForm->notice_photo) }}" alt="Notice Photo" class="max-w-xs rounded">
                            </div>
                        @endif
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4">
                            <input type="file" name="notice_photo" accept="image/*" class="w-full">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">📸 Upload a new image to replace the current one (JPG, PNG, GIF - Max 5MB)</p>
                        </div>
                        @error('notice_photo')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Start Date & Time *</label>
                            <input type="datetime-local" name="start_date" required class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-500 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900 transition" value="{{ old('start_date', $registrationForm->start_date->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">End Date & Time *</label>
                            <input type="datetime-local" name="end_date" required class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-500 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900 transition" value="{{ old('end_date', $registrationForm->end_date->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $registrationForm->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm font-bold text-gray-700 dark:text-gray-300">Active</span>
                        </label>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">
                            Update Form
                        </button>
                        <a href="{{ route('admin.registration-forms.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

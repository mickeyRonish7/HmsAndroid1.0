<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">Published Registration Forms</h2>
            <a href="{{ route('admin.registration-forms.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Create New Form
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-green-800 font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">End Date</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Submissions</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($forms as $form)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $form->title }}</div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($form->description, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $form->start_date->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $form->end_date->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($form->isCurrentlyActive())
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-800">Active Now</span>
                                        @elseif($form->is_active)
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-yellow-100 text-yellow-800">Scheduled</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-gray-100 text-gray-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('admin.registration-forms.submissions', $form->id) }}" class="text-blue-600 hover:underline font-bold">
                                            {{ $form->submissions_count }} Submissions
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                        <form action="{{ route('admin.registration-forms.toggle', $form->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 rounded text-xs font-bold transition {{ $form->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                                {{ $form->is_active ? '✓ Enabled' : '✕ Disabled' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.registration-forms.edit', $form->id) }}" class="text-blue-600 hover:underline">Edit</a>
                                        <form action="{{ route('admin.registration-forms.destroy', $form->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this form and all submissions?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <p>No registration forms created yet.</p>
                                        <a href="{{ route('admin.registration-forms.create') }}" class="text-blue-600 hover:underline">Create your first form</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($forms->hasPages())
                    <div class="px-6 py-4 border-t">
                        {{ $forms->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

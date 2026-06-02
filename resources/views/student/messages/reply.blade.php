<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reply to Message') }}
            </h2>
            <a href="{{ route('student.messages.show', $message->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Original Message Preview -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('Original Message') }}</h3>
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $message->subject }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ __('From') }}: {{ $message->sender->name }} | {{ $message->created_at->format('M d, Y H:i') }}</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-4 whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>
                </div>
            </div>

            <!-- Reply Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('student.messages.store-reply', $message->id) }}" method="POST">
                        @csrf

                        <!-- Message -->
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Your Reply') }}</label>
                            <textarea id="message" name="message" rows="8" placeholder="Type your reply here..." class="w-full px-4 py-2 border-2 border-gray-300 dark:border-gray-500 rounded-lg dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900 transition" required>{{ old('message') }}</textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ __('Be clear and professional in your message.') }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('student.messages.show', $message->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ __('Send Reply') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Back to Inbox Button -->
            <div class="mt-6">
                <a href="{{ route('student.messages.inbox') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    {{ __('Back to Inbox') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

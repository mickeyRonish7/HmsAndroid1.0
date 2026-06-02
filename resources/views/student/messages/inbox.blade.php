<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Messages') }}
                @if($messages->total() > 0)
                    <span class="ml-2 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm font-semibold">
                        {{ $messages->total() }}
                    </span>
                @endif
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($messages->count() > 0)
                        <div class="space-y-4">
                            @foreach($messages as $message)
                                <div class="flex items-start p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <!-- Sender Avatar -->
                                    <div class="flex-shrink-0 mr-4">
                                        @if($message->sender->profile_photo_path)
                                            <img src="{{ asset('storage/' . $message->sender->profile_photo_path) }}" alt="{{ $message->sender->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-blue-200 dark:bg-blue-900 flex items-center justify-center border-2 border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-300 font-bold text-lg">
                                                {{ substr($message->sender->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Message Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $message->sender->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->format('M d, Y \a\t H:i') }}</p>
                                            </div>
                                            @if(!$message->is_read)
                                                <span class="ml-4 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs font-semibold rounded-full">
                                                    {{ __('New') }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-2">{{ $message->subject }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ Str::limit($message->message, 150) }}</p>
                                    </div>

                                    <!-- Action Button -->
                                    <div class="ml-4 flex-shrink-0">
                                        <a href="{{ route('student.messages.show', $message->id) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
                                            {{ __('Read') }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $messages->links() }}
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('No Messages') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('You haven\'t received any messages yet.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

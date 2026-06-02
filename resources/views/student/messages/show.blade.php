<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $message->subject }}
            </h2>
            <a href="{{ route('student.messages.inbox') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                {{ __('Back to Inbox') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Message Header -->
                <div class="border-b border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $message->subject }}</h3>
                            <div class="flex items-center gap-4 mt-4">
                                <div class="flex items-center">
                                    @if($message->sender->profile_photo_path)
                                        <img src="{{ asset('storage/' . $message->sender->profile_photo_path) }}" alt="{{ $message->sender->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600 mr-3">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-blue-200 dark:bg-blue-900 flex items-center justify-center mr-3 border-2 border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-300 font-bold text-lg">
                                            {{ substr($message->sender->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $message->sender->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $message->sender->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $message->created_at->format('M d, Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">
                                        {{ $message->created_at->format('H:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message Body -->
                <div class="p-6">
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>
                </div>

                <!-- Footer with Actions -->
                <div class="border-t border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-700/30">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            @if($message->is_read)
                                <span class="text-green-600 dark:text-green-400 font-semibold">✓ {{ __('Read on') }} {{ $message->read_at->format('M d, Y \a\t H:i') }}</span>
                            @else
                                <span class="text-blue-600 dark:text-blue-400 font-semibold">{{ __('Unread') }}</span>
                            @endif
                        </p>
                        <div class="flex gap-2">
                            <a href="{{ route('student.messages.reply-form', $message->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l-6 6"></path></svg>
                                {{ __('Reply') }}
                            </a>
                            <form action="{{ route('student.messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this message?') }}');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    {{ __('Delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
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

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Message') }}
            </h2>
            <div class="flex gap-2">
                @if(request()->has('inbox'))
                    <a href="{{ route('admin.messages.inbox') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                        {{ __('Back to Inbox') }}
                    </a>
                @else
                    <a href="{{ route('admin.messages.sent') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                        {{ __('Back to Sent') }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Original Message -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            @if($message->sender->profile_photo_path)
                                <img src="{{ asset('storage/' . $message->sender->profile_photo_path) }}" alt="{{ $message->sender->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                            @else
                                <div class="w-12 h-12 rounded-full bg-blue-200 dark:bg-blue-900 flex items-center justify-center border-2 border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-300 font-bold text-lg">
                                    {{ substr($message->sender->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('From:') }} <span class="font-semibold text-gray-900 dark:text-white">{{ $message->sender->name }}</span>
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $message->created_at->format('F d, Y \a\t H:i A') }}
                                </p>
                            </div>
                        </div>
                        @if(!$message->is_read && $message->to_user_id === Auth::id())
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs font-semibold rounded-full">
                                {{ __('New') }}
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $message->subject }}</h3>
                    
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>
                </div>
            </div>

            <!-- Replies Section -->
            @if($message->replies()->count() > 0)
                <div class="space-y-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Replies') }} ({{ $message->replies()->count() }})</h3>
                    
                    @foreach($message->replies as $reply)
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <div class="flex items-start gap-3 mb-4">
                                    @if($reply->sender->profile_photo_path)
                                        <img src="{{ asset('storage/' . $reply->sender->profile_photo_path) }}" alt="{{ $reply->sender->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600 flex-shrink-0\">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-green-200 dark:bg-green-900 flex items-center justify-center border-2 border-gray-300 dark:border-gray-600 text-green-600 dark:text-green-300 font-bold text-sm flex-shrink-0\">
                                            {{ substr($reply->sender->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('From:') }} <span class="font-semibold text-gray-900 dark:text-white\">{{ $reply->sender->name }}</span>
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1\">
                                            {{ $reply->created_at->format('F d, Y \a\t H:i A') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="prose dark:prose-invert max-w-none">
                                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $reply->message }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                @if($message->to_user_id === Auth::id() && $message->reply_to_id === null)
                    <div class="bg-gray-50 dark:bg-gray-700/30 border border-gray-200 dark:border-gray-600 rounded-lg p-6 mb-6">
                        <p class="text-center text-gray-600 dark:text-gray-400">{{ __('No replies yet') }}</p>
                    </div>
                @endif
            @endif

            <!-- Send Reply Form (Only if admin is recipient and this is an original message) -->
            @if($message->to_user_id === Auth::id() && $message->reply_to_id === null)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Send Reply') }}</h3>
                        
                        <form method="POST" action="{{ route('admin.messages.store-reply', $message->id) }}">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('Message') }}
                                </label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="5"
                                    class="w-full border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-300 font-sans"
                                    placeholder="{{ __('Type your reply here...') }}"
                                    required
                                ></textarea>
                                @error('message')
                                    <p class="text-red-600 dark:text-red-400 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end gap-4">
                                <a href="{{ request()->query('from') ? route('admin.messages.inbox') : route('admin.messages.sent') }}" class="inline-flex items-center px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white text-sm font-semibold rounded-lg transition">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    {{ __('Send Reply') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

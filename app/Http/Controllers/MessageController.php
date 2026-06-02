<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MessageController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Show admin's interface to send message to a student
     */
    public function sendForm($studentId)
    {
        $student = User::where('role', 'student')->findOrFail($studentId);
        return view('admin.messages.send', compact('student'));
    }

    /**
     * Store message from admin to student
     */
    public function store(Request $request)
    {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $recipient = User::findOrFail($request->to_user_id);

        // Only admins can send messages, only to students
        if (Auth::user()->role !== 'admin' || $recipient->role !== 'student') {
            return back()->with('error', 'Unauthorized action.');
        }

        $message = Message::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $request->to_user_id,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // Log the action
        \App\Services\AuditLogger::log('send_message', 'Message', $message, "Admin sent message to {$recipient->name}");

        // Create notification for student
        $this->notificationService->notifyUser(
            $recipient->id,
            'message_received',
            __('New Message'),
            __('You have received a new message from Admin'),
            ['message_id' => $message->id, 'subject' => $message->subject],
            route('student.messages.show', $message->id)
        );

        // Invalidate recipient's unread message count cache
        Cache::forget("user:{$recipient->id}:unread_message_count");

        return back()->with('success', 'Message sent successfully to ' . $recipient->name);
    }

    /**
     * View student's inbox
     */
    public function inbox()
    {
        $messages = Message::where('to_user_id', Auth::id())
                    ->with('sender')
                    ->latest()
                    ->paginate(15);

        return view('student.messages.inbox', compact('messages'));
    }

    /**
     * View a specific message
     */
    public function show($id)
    {
        $message = Message::findOrFail($id);

        // Ensure user can only view their own messages
        if ($message->to_user_id !== Auth::id() && $message->from_user_id !== Auth::id()) {
            abort(403);
        }

        // Mark as read if recipient is viewing
        if ($message->to_user_id === Auth::id()) {
            $message->markAsRead();
            // Clear unread count cache
            Cache::forget("user:" . Auth::id() . ":unread_message_count");
        }

        return view('student.messages.show', compact('message'));
    }

    /**
     * Delete a message
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        // Ensure user can only delete their own messages
        if ($message->to_user_id !== Auth::id() && $message->from_user_id !== Auth::id()) {
            abort(403);
        }

        // Invalidate unread message count cache for both parties
        Cache::forget("user:{$message->to_user_id}:unread_message_count");
        Cache::forget("user:{$message->from_user_id}:unread_message_count");

        $message->delete();

        return back()->with('success', 'Message deleted successfully.');
    }

    /**
     * Show reply form for a message
     */
    public function replyForm($id)
    {
        $message = Message::findOrFail($id);

        // Only recipients can reply
        if ($message->to_user_id !== Auth::id()) {
            abort(403);
        }

        return view('student.messages.reply', compact('message'));
    }

    /**
     * Store a reply to a message
     */
    public function storeReply(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        // Only recipients can reply
        if ($message->to_user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        // Create reply message
        $reply = Message::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $message->from_user_id, // Send to original sender
            'subject' => 'Re: ' . $message->subject,
            'message' => $request->message,
            'reply_to_id' => $id,
        ]);

        // Log the action
        \App\Services\AuditLogger::log('send_reply', 'Message', null, "Student replied to message: {$message->subject}");

        // Create notification for admin
        $sender = Auth::user();
        $this->notificationService->notifyUser(
            $message->from_user_id,
            'message_reply_received',
            __('New Reply'),
            __('You have received a reply from ') . $sender->name,
            ['message_id' => $reply->id, 'subject' => $reply->subject, 'student_id' => $sender->id],
            route('admin.messages.show', $message->id)
        );

        // Invalidate recipient's unread message count cache
        Cache::forget("user:{$message->from_user_id}:unread_message_count");

        return redirect()->route('student.messages.show', $id)->with('success', 'Reply sent successfully!');
    }

    /**
     * Get unread message count (for JSON response)
     * Uses cache to reduce database queries
     */
    public function unreadCount()
    {
        $userId = Auth::id();
        $cacheKey = "user:{$userId}:unread_message_count";
        
        // Try to get from cache first (cached for 30 seconds)
        $count = Cache::remember($cacheKey, 30, function () {
            return Message::where('to_user_id', Auth::id())
                        ->where('is_read', false)
                        ->count();
        });

        return response()->json(['unread_count' => $count]);
    }

    /**
     * View admin's inbox (for replies from students)
     */
    public function adminInbox()
    {
        $messages = Message::where('to_user_id', Auth::id())
                    ->where('from_user_id', '!=', Auth::id())
                    ->with('sender')
                    ->latest()
                    ->paginate(15);

        return view('admin.messages.inbox', compact('messages'));
    }

    /**
     * View admin's sent messages
     */
    public function adminSentMessages()
    {
        $messages = Message::where('from_user_id', Auth::id())
                    ->with('recipient')
                    ->withCount('replies')
                    ->latest()
                    ->paginate(15);

        return view('admin.messages.sent', compact('messages'));
    }

    /**
     * View a message (for admin)
     */
    public function adminShowMessage($id)
    {
        $message = Message::with(['sender', 'recipient', 'replies'])->findOrFail($id);

        // Ensure user can only view their own messages
        if ($message->to_user_id !== Auth::id() && $message->from_user_id !== Auth::id()) {
            abort(403);
        }

        // Mark as read if recipient is viewing
        if ($message->to_user_id === Auth::id()) {
            $message->markAsRead();
        }

        return view('admin.messages.show', compact('message'));
    }

    /**
     * Store admin's reply to a student message
     */
    public function adminStoreReply(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        // Only admins can reply to student messages (where they are recipient)
        if ($message->to_user_id !== Auth::id() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        // Create reply message
        $reply = Message::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $message->from_user_id, // Send back to student
            'subject' => 'Re: ' . $message->subject,
            'message' => $request->message,
            'reply_to_id' => $id,
        ]);

        // Log the action
        \App\Services\AuditLogger::log('send_reply', 'Message', null, "Admin replied to student message: {$message->subject}");

        // Create notification for student
        $this->notificationService->notifyUser(
            $message->from_user_id,
            'message_received',
            __('New Message'),
            __('You have received a new message from Admin'),
            ['message_id' => $reply->id, 'subject' => $reply->subject],
            route('student.messages.show', $reply->id)
        );

        // Invalidate recipient's (student's) unread message count cache
        Cache::forget("user:{$message->from_user_id}:unread_message_count");

        return redirect()->route('admin.messages.show', $id)->with('success', 'Reply sent successfully!');
    }
}

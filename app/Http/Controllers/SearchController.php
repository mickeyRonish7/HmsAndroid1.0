<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function adminSearch(Request $request)
    {
        $query = trim($request->input('query'));
        $type = $request->input('type', 'all');

        // Allow search if type is selected OR query is present
        if (empty($query) && $type === 'all') {
            return back();
        }

        // Direct Access: If query matches Student ID exactly, go straight to profile
        $exactStudent = \App\Models\User::where('role', 'student')
                        ->where('student_id_number', $query)
                        ->first();
        
        if ($exactStudent) {
            return redirect()->route('admin.students.profile', $exactStudent->id);
        }

        // Direct Access: If query matches Room Number exactly, go straight to room details
        $exactRoom = \App\Models\Room::where('room_number', $query)->first();
        
        if ($exactRoom) {
            return redirect()->route('admin.rooms.show', $exactRoom->id);
        }

        $students = collect();
        $rooms = collect();
        $complaints = collect();
        $visitors = collect();
        $fees = collect();
        $notices = collect();

        // 1. Students
        if ($type === 'all' || $type === 'students') {
            $q = \App\Models\User::where('role', 'student');
            if ($query) {
                $q->where(function($sub) use ($query) {
                    $sub->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('student_id_number', 'like', "%{$query}%");
                });
            }
            $students = $q->latest()->take(50)->get();
        }
        
        // 2. Rooms
        if ($type === 'all' || $type === 'rooms') {
            $q = \App\Models\Room::query();
            if ($query) {
                $q->where('room_number', 'like', "%{$query}%");
            }
            $rooms = $q->get();
        }

        // 3. Complaints
        if ($type === 'all' || $type === 'complaints') {
            $q = \App\Models\Complaint::with('student');
            if ($query) {
                $q->where(function($sub) use ($query) {
                     $sub->where('description', 'like', "%{$query}%")
                         ->orWhere('category', 'like', "%{$query}%");
                });
            }
            $complaints = $q->latest()->take(50)->get();
        }

        // 4. Visitors
        if ($type === 'all' || $type === 'visitors') {
            $q = \App\Models\Visitor::with('student');
            if ($query) {
                $q->where(function($sub) use ($query) {
                    $sub->where('visitor_name', 'like', "%{$query}%")
                        ->orWhere('purpose', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%");
                });
            }
            $visitors = $q->latest()->take(50)->get();
        }

        // 5. Fees
        if ($type === 'all' || $type === 'fees') {
            $q = \App\Models\Fee::with('student');
            if ($query) {
                 $q->where(function($sub) use ($query) {
                    $sub->where('type', 'like', "%{$query}%")
                        ->orWhere('status', 'like', "%{$query}%");
                }); // Note: Searching fees by student name is harder without joining, keeping simple for now
            }
            $fees = $q->latest()->take(50)->get();
        }

        // 6. Notices
        if ($type === 'all' || $type === 'notices') {
            $q = \App\Models\Notice::query();
            if ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            }
            $notices = $q->latest()->take(20)->get();
        }

        return view('admin.search_results', compact('students', 'rooms', 'complaints', 'visitors', 'fees', 'notices', 'query', 'type'));
    }

    public function studentSearch(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type', 'all');

        if (empty($query) && $type === 'all') {
            return back();
        }
        
        $user = \Illuminate\Support\Facades\Auth::user();

        $notices = collect();
        $myComplaints = collect();
        $fees = collect();

        // 1. Notices
        if ($type === 'all' || $type === 'notices') {
            $q = \App\Models\Notice::where(function($sub) {
                $sub->where('audience', 'all')->orWhere('audience', 'students');
            });

            if ($query) {
                $q->where(function($sub) use ($query) {
                    $sub->where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                });
            }
            $notices = $q->latest()->take(20)->get();
        }

        // 2. My Complaints
        if ($type === 'all' || $type === 'complaints') {
            $q = \App\Models\Complaint::where('student_id', $user->id);
            if ($query) {
                $q->where(function($sub) use ($query) {
                    $sub->where('description', 'like', "%{$query}%")
                        ->orWhere('category', 'like', "%{$query}%");
                });
            }
            $myComplaints = $q->latest()->get();
        }

         // 3. My Fees
        if ($type === 'all' || $type === 'fees') {
            $q = \App\Models\Fee::where('student_id', $user->id);
            if ($query) {
                $q->where(function($sub) use ($query) {
                    $sub->where('type', 'like', "%{$query}%")
                        ->orWhere('status', 'like', "%{$query}%")
                        ->orWhere('amount', 'like', "%{$query}%");
                });
            }
            $fees = $q->latest()->get();
        }


        return view('student.search_results', compact('notices', 'myComplaints', 'fees', 'query', 'type'));
    }
}

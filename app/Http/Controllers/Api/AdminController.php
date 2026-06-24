<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Fee;
use App\Models\Room;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'The provided credentials do not match our records.',
            ], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json([
                'status'  => 'error',
                'message' => 'The provided credentials do not match our records.',
            ], 401);
        }

        if ($user->is_active === false) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Your account has been deactivated. Please contact the administrator.',
            ], 403);
        }

        AuditLogger::log('login', 'Auth', $user, 'Admin mobile login');

        $user->tokens()->where('name', 'admin-mobile')->delete();
        $token = $user->createToken('admin-mobile')->plainTextToken;

        return response()->json([
            'status'  => 'success',
            'message' => 'Login successful.',
            'token'   => $token,
            'admin'   => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Access denied. This endpoint is for admins only.',
            ], 403);
        }

        $totalStudents  = User::where('role', 'student')->count();
        $totalRooms     = Room::count();
        $totalComplaints = Complaint::count();

        $dueFeesQuery = Fee::whereIn('status', ['pending', 'overdue']);
        $dueFeesCount = (clone $dueFeesQuery)->count();
        $dueFeesAmount = (clone $dueFeesQuery)->sum('amount');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'total_students'   => $totalStudents,
                'total_rooms'      => $totalRooms,
                'total_complaints' => $totalComplaints,
                'due_fees'         => $dueFeesCount,
                'due_fees_amount'  => round((float) $dueFeesAmount, 2),
            ],
        ]);
    }
}

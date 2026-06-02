<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use App\Models\Notice;
use App\Models\Complaint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SearchDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Student
        if (!User::where('email', 'john.doe@example.com')->exists()) {
            User::create([
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => '1234567890',
                'parent_phone' => '0987654321',
                'year' => 1,
                'semester' => 2,
                'department' => 'Computer Science',
                'address' => '123 Main St',
                'student_id_number' => 'STD-2024-001',
                'is_approved' => true
            ]);
        }

        // 2. Create Rooms
        if (!Room::where('room_number', '101-A')->exists()) {
            Room::create([
                'room_number' => '101-A',
                'type' => 'single',
                'capacity' => 1,
                'status' => 'available'
            ]);
        }
        
        // 3. Create Notices (Audience: All or Students)
        Notice::create([
            'title' => 'Important: Hostel Rules Update',
            'content' => 'Please note that the gate closing time has been changed to 9:30 PM effective immediately.',
            'audience' => 'all',
        ]);
        
        Notice::create([
            'title' => 'Exam Schedule Notification',
            'content' => 'Final exams start next week. Quiet hours are 24/7.',
            'audience' => 'students',
        ]);

        // 4. Create Complaints (linked to the student)
        $student = User::where('email', 'john.doe@example.com')->first();
        if ($student) {
            Complaint::create([
                'student_id' => $student->id,
                'category' => 'Maintenance',
                'description' => 'The fan in room 101-A is making a loud noise.',
                'status' => 'pending'
            ]);
        }
    }
}

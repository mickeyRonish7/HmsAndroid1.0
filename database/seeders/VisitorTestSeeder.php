<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Visitor;
use Carbon\Carbon;

class VisitorTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a student user
        $student = User::where('role', 'student')->first();
        
        if (!$student) {
            echo "No student found. Please create a student first.\n";
            return;
        }

        // Get a visitor user or create one
        $visitor = User::where('role', 'visitor')->first();
        
        if (!$visitor) {
            $visitor = User::create([
                'name' => 'Test Visitor',
                'email' => 'visitor@test.com',
                'password' => bcrypt('password'),
                'role' => 'visitor',
                'phone' => '9800000000',
                'student_approved' => 0,
                'admin_approved' => 0,
                'is_active' => true,
            ]);
        }

        // Create pending visit request
        Visitor::create([
            'user_id' => $visitor->id,
            'student_id' => $student->id,
            'visitor_name' => $visitor->name,
            'phone' => $visitor->phone,
            'purpose' => 'Family Visit',
            'entry_time' => null,
            'exit_time' => null,
            'status' => 'pending',
            'visit_date' => Carbon::tomorrow()->toDateString(),
        ]);

        // Create another pending visit request from a different visitor
        $visitor2 = User::create([
            'name' => 'Test Visitor 2',
            'email' => 'visitor2@test.com',
            'password' => bcrypt('password'),
            'role' => 'visitor',
            'phone' => '9800000001',
            'student_approved' => 0,
            'admin_approved' => 0,
            'is_active' => true,
        ]);

        Visitor::create([
            'user_id' => $visitor2->id,
            'student_id' => $student->id,
            'visitor_name' => $visitor2->name,
            'phone' => $visitor2->phone,
            'purpose' => 'College Event',
            'entry_time' => null,
            'exit_time' => null,
            'status' => 'pending',
            'visit_date' => Carbon::now()->addDays(2)->toDateString(),
        ]);

        echo "✓ Test visitor data created successfully!\n";
        echo "  - Student: {$student->name}\n";
        echo "  - Visitor 1: {$visitor->name}\n";
        echo "  - Visitor 2: {$visitor2->name}\n";
    }
}

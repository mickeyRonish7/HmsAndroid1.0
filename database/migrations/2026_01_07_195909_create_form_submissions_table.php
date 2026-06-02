<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_form_id')->constrained()->onDelete('cascade');
            $table->string('student_number');
            $table->string('student_id_no');
            $table->string('student_name');
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->string('phone_number');
            $table->string('email');
            $table->string('semester');
            $table->string('department');
            $table->string('id_card_photo')->nullable();
            $table->json('application_photos')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};

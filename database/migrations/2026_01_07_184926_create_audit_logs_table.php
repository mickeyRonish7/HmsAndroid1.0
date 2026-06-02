<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // e.g., 'created', 'updated', 'deleted', 'login', 'logout'
            $table->string('module')->nullable(); // e.g., 'Room', 'Fee', 'Complaint'
            $table->string('model_type')->nullable(); 
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('details')->nullable(); // JSON or text description
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

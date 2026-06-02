<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registration_forms', function (Blueprint $table) {
            $table->string('notice_photo')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('registration_forms', function (Blueprint $table) {
            $table->dropColumn('notice_photo');
        });
    }
};

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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('disabled_at')->nullable()->index();
            $table->timestamp('disabled_until')->nullable();
            $table->string('disabled_reason')->nullable();
            $table->foreignId('disabled_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('disabled_by');
            $table->dropIndex(['disabled_at']);
            $table->dropColumn(['disabled_at', 'disabled_until', 'disabled_reason']);
        });
    }
};

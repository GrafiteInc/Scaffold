<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwoFactorToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('two_factor_platform')->after('email_verified_at')->nullable();
            $table->string('two_factor_code')->after('two_factor_platform')->nullable();
            $table->dateTime('two_factor_expires_at')->after('two_factor_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('two_factor_platform');
            $table->dropColumn('two_factor_code');
            $table->dropColumn('two_factor_expires_at');
        });
    }
}

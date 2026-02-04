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
        Schema::table('companies', function (Blueprint $table) {

            $table->timestamp('email_verified_at')->nullable()->after('remember_token');
            $table->timestamp('last_login_at')->nullable()->after('email_verified_at');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'company_desc',
                'password',
                'remember_token',
                'email_verified_at',
                'last_login_at',
                'last_login_ip',
                'status'
            ]);
        });
    }
};
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
            // account 用來存員編(SSO)或自訂帳號(本地)
            $table->string('account')->unique()->after('id');
            // user_type 用來標記：'sso' 或 'local'
            $table->string('user_type')->default('local')->after('account');
            
            // 保持 email 欄位可為空，因為 SSO 登入者可能沒提供 Email
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};

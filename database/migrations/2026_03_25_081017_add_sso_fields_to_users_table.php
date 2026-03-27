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
            // 增加 SSO 相關欄位，並允許為空 (nullable) 供本地帳號使用
            $table->string('pkind')->nullable()->after('user_type');   // 人員類別
            $table->string('grpno')->nullable()->after('pkind');       // 群組代碼
            $table->string('unicode1')->nullable()->after('grpno');    // 單位代碼1
            $table->string('dpt_desc1')->nullable()->after('unicode1');// 單位名稱1
            $table->string('unicode2')->nullable()->after('dpt_desc1');// 單位代碼2
            $table->string('titcod')->nullable()->after('unicode2');   // 職稱代碼
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pkind', 'grpno', 'unicode1', 'dpt_desc1', 'unicode2', 'titcod']);
        }); 
    }
};

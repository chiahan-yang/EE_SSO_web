<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 補足之前遺漏或新增的欄位
            $table->string('idno')->nullable()->after('name');    // 身分證號
            $table->string('title')->nullable()->after('titcod'); // 職稱名稱
            $table->string('leave')->nullable()->after('title');  // 在職註記
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

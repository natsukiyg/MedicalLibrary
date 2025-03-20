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
        Schema::table('manuals', function (Blueprint $table) {
            $table->json('files')->nullable()->comment('閲覧専用のファイルのリスト'); 
            $table->json('editable_files')->nullable()->comment('編集可能なファイルのリスト');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manuals', function (Blueprint $table) {
            $table->dropColumn(['files', 'editable_files']);
        });
    }
};

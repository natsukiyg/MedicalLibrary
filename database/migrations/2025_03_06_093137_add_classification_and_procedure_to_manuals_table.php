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
            // classification_id を manuals テーブルに追加。必要ならnullableに設定
            $table->unsignedBigInteger('classification_id')->nullable()->after('specialty_id');
            // procedure_id を manuals テーブルに追加
            $table->unsignedBigInteger('procedure_id')->nullable()->after('classification_id');

            // 外部キー制約を追加。対象テーブルとカラムが存在している前提です。
            $table->foreign('classification_id')
                ->references('id')->on('classifications')
                ->onDelete('set null'); // 分類が削除された場合は null にするなど

            $table->foreign('procedure_id')
                ->references('id')->on('procedures')
                ->onDelete('set null'); // 術式が削除された場合は null にする
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manuals', function (Blueprint $table) {
            // 外部キー制約の削除
            $table->dropForeign(['classification_id']);
            $table->dropForeign(['procedure_id']);
            // カラムの削除
            $table->dropColumn(['classification_id', 'procedure_id']);
        });
    }
};

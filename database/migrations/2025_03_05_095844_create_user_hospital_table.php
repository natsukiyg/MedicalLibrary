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
        Schema::create('user_hospital', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('hospital_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('specialty_id')->nullable();
            $table->integer('role')->comment('0:スタッフ, 1:チームメンバー, 2:管理者, 3:運営者');
            $table->tinyInteger('approval_status')->default(0)
                  ->comment('0:pending, 1:approved, 2:denied'); //承認ステータス 0:未承認, 1:承認済, 2:拒否
            $table->text('rejection_reason')->nullable(); //拒否時の理由
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_hospital');
    }
};

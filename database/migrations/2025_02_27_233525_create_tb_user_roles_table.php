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
        Schema::create('tb_user_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('role_id')->index();
            $table->softDeletes();
            $table->timestamp('created_at')->default()->useCurrent();

            $table->foreign('role_id')->references('id')->on('tb_roles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('tb_users')->onDelete('cascade');

            $table->primary(['id', 'role_id', 'user_id']);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_user_roles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_user_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('role_id')->index();
            $table->timestamp('created_at')->default()->useCurrent();

            $table->foreign('role_id')->references('id')->on('tb_roles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('tb_users')->onDelete('cascade');

            $table->primary(['role_id', 'user_id']);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::table('tb_user_roles', function(Blueprint $table) {
            DB::statement("ALTER TABLE tb_user_roles ADD id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD INDEX (id)");
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

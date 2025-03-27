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
        Schema::create('tb_role_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id')->index();
            $table->unsignedBigInteger('role_id')->index();
            $table->timestamp('created_at')->default()->useCurrent();

            $table->foreign('role_id')->references('id')->on('tb_roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('tb_permissions')->onDelete('cascade');

            $table->primary(['role_id', 'permission_id']);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::table('tb_role_permissions', function(Blueprint $table) {
            DB::statement("ALTER TABLE tb_role_permissions ADD id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD INDEX (id)");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_role_permissions');
    }
};

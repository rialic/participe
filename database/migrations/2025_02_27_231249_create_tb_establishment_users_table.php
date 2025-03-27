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
        Schema::create('tb_establishment_users', function (Blueprint $table) {
            $table->unsignedBigInteger('establishment_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('cbo_id')->index();
            $table->boolean('primary_bond')->default(false);
            $table->softDeletes();
            $table->timestamp('created_at')->default()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();


            $table->foreign('establishment_id')->references('id')->on('tb_establishments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('tb_users')->onDelete('cascade');
            $table->foreign('cbo_id')->references('id')->on('tb_cbos')->onDelete('cascade');

            $table->primary(['establishment_id', 'user_id', 'cbo_id']);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::table('tb_establishment_users', function(Blueprint $table) {
            DB::statement("ALTER TABLE tb_establishment_users ADD id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD INDEX (id)");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_establishment_users');
    }
};

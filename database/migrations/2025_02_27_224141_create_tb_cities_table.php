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
        Schema::create('tb_cities', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('name');
            $table->unsignedBigInteger('datacnes_id');
            $table->softDeletes();
            $table->timestamps();

            $table->unsignedBigInteger('micro_zone_id')->index()->nullable();
            $table->unsignedBigInteger('state_id')->index();

            $table->foreign('micro_zone_id')->references('id')->on('tb_micro_zones');
            $table->foreign('state_id')->references('id')->on('tb_states');

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
        Schema::dropIfExists('tb_cities');
    }
};

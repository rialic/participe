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
        Schema::create('tb_teams', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->unsignedBigInteger('ine');
            $table->string('name_team');
            $table->string('type_team');
            $table->timestamp('active_at');
            $table->timestamp('deactivate_at')->nullable();
            $table->unsignedBigInteger('datacnes_city_id');
            $table->unsignedBigInteger('datacnes_area_id');
            $table->unsignedBigInteger('datacnes_team_id');
            $table->softDeletes();
            $table->timestamps();

            $table->unsignedBigInteger('establishment_id')->index()->nullable();

            $table->foreign('establishment_id')->references('id')->on('tb_establishments');

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
        Schema::dropIfExists('tb_teams');
    }
};

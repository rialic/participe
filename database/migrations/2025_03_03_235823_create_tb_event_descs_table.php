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
        Schema::create('tb_event_descs', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement()->index();
            $table->unsignedBigInteger('event_id')->index();
            $table->unsignedBigInteger('descs_id')->index();
            $table->softDeletes();
            $table->timestamp('created_at')->default()->useCurrent();

            $table->foreign('event_id')->references('id')->on('tb_events')->onDelete('cascade');
            $table->foreign('descs_id')->references('id')->on('tb_descs')->onDelete('cascade');

            $table->primary(['id', 'event_id', 'descs_id']);

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
        Schema::dropIfExists('tb_event_descs');
    }
};

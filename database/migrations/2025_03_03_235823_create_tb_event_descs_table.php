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
        Schema::create('tb_event_descs', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->index();
            $table->unsignedBigInteger('descs_id')->index();
            $table->timestamp('created_at')->default()->useCurrent();

            $table->foreign('event_id')->references('id')->on('tb_events')->onDelete('cascade');
            $table->foreign('descs_id')->references('id')->on('tb_descs')->onDelete('cascade');

            $table->primary(['event_id', 'descs_id']);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::table('tb_event_descs', function(Blueprint $table) {
            DB::statement("ALTER TABLE tb_event_descs ADD id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD INDEX (id)");
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

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
        Schema::create('tb_event_participants', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->integer('rating_event')->default(0);
            $table->integer('rating_event_schedule')->default(0);
            $table->text('hint')->nullable();
            $table->timestamp('rated_at')->nullable();
            $table->timestamp('created_at')->default()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->index()->nullable();

            $table->foreign('user_id')->references('id')->on('tb_users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('tb_events')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('tb_users');

            $table->primary(['user_id', 'event_id']);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        Schema::table('tb_event_participants', function(Blueprint $table) {
            DB::statement("ALTER TABLE tb_event_participants ADD id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD INDEX (id)");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_event_participants');
    }
};

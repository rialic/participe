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
        Schema::create('tb_event_participants', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement()->index();
            $table->unsignedBigInteger('event_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->integer('rating_event')->default(0);
            $table->integer('rating_event_schedule')->default(0);
            $table->text('hint')->nullable();
            $table->timestamp('rated_at')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->default()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('user_id')->references('id')->on('tb_users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('tb_events')->onDelete('cascade');

            $table->primary(['id', 'user_id', 'event_id']);

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
        Schema::dropIfExists('tb_event_participants');
    }
};

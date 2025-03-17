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
        Schema::create('tb_events', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('name');
            $table->enum('type_event', ['Curso', 'Webaulas/palestras', 'Webseminários', 'Fórum de discussão', 'Reunião de matriciamento'])->default('Curso');
            $table->enum('organization', ['TSMS', 'Fiocruz']);
            $table->string('room_link', 500);
            $table->timestamp('start_at');
            $table->integer('start_minutes_additions')->default(30);
            $table->timestamp('end_at');
            $table->integer('end_minutes_additions')->default(30);
            $table->integer('workload');
            $table->softDeletes();
            $table->timestamps();

            $table->unsignedBigInteger('deleted_by')->index()->nullable();
            $table->unsignedBigInteger('created_by')->index()->nullable();

            $table->foreign('created_by')->references('id')->on('tb_users');
            $table->foreign('deleted_by')->references('id')->on('tb_users');

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
        Schema::dropIfExists('tb_events');
    }
};

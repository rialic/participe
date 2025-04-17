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
        Schema::table('tb_events', function (Blueprint $table) {
            $table->enum('type_notification', ['all', 'cities', 'group', 'none'])->default('none');
            $table->json('cities_to_notify')->nullable();
            $table->json('select_group_emails')->nullable();
            $table->json('summary_emails')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_events', function (Blueprint $table) {
            $table->dropColumn(['type_notification', 'cities_to_notify', 'select_group_emails', 'summary_emails']);
        });
    }
};

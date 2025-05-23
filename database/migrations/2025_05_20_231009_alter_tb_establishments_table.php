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
        Schema::table('tb_establishments', function (Blueprint $table) {
            $table->boolean('tdiagn')->after('sus')->default(0);
            $table->boolean('teduca')->after('tdiagn')->default(0);
            $table->boolean('tconsul')->after('teduca')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_establishments', function (Blueprint $table) {
            $table->dropColumn(['tdiagn', 'teduca', 'tconsul']);
        });
    }
};

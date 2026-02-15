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
        Schema::table('maps', function (Blueprint $table) {
            $table->string('container_border_radius', 10)->default('0')->after('google_map_id');
            $table->string('container_border', 50)->default('')->after('container_border_radius');
            $table->string('container_shadow', 10)->default('none')->after('container_border');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maps', function (Blueprint $table) {
            $table->dropColumn(['container_border_radius', 'container_border', 'container_shadow']);
        });
    }
};

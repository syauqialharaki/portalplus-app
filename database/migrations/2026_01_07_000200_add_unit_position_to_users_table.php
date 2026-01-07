<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('unit_id')->nullable()->after('institution_id')->constrained('units')->nullOnDelete();
            $table->foreignUuid('position_id')->nullable()->after('unit_id')->constrained('positions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['position_id']);
            $table->dropColumn(['unit_id', 'position_id']);
        });
    }
};

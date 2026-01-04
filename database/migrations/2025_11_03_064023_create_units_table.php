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
        Schema::create('units', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name'); // contoh: 'Bagian Akademik', 'Keuangan', 'IT Support'
            $table->string('code', 20)->unique()->nullable(); // contoh: 'AKD', 'KEU'
            $table->foreignUuid('parent_id')->nullable()->constrained('units')->nullOnDelete(); // untuk struktur hirarki (opsional)
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};

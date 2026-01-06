<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('key_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->foreignUuid('okr_id')->constrained('okrs')->cascadeOnDelete();
            $table->string('title');
            $table->string('metric_type')->default('number'); // number, percentage, binary, currency
            $table->decimal('target', 15, 4)->default(0);
            $table->decimal('current', 15, 4)->default(0);
            $table->string('unit')->nullable();
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->string('status')->default('not_started');
            $table->decimal('confidence_score', 4, 2)->default(0.00);
            $table->date('due_date')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['institution_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('key_results');
    }
};

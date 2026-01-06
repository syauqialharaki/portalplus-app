<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('check_ins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->foreignUuid('okr_id')->nullable()->constrained('okrs')->nullOnDelete();
            $table->foreignUuid('key_result_id')->nullable()->constrained('key_results')->nullOnDelete();
            $table->text('note')->nullable();
            $table->decimal('current_value', 15, 4)->nullable();
            $table->decimal('confidence_score', 4, 2)->default(0.00);
            $table->boolean('has_blocker')->default(false);
            $table->text('blocker')->nullable();
            $table->text('next_steps')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['institution_id', 'okr_id', 'key_result_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_ins');
    }
};

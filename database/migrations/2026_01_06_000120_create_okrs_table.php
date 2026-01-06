<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('okrs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('institution_id')->constrained('institutions')->cascadeOnDelete();
            $table->uuidMorphs('owner'); // owner_type (User/Unit), owner_id
            $table->foreignUuid('period_id')->constrained('okr_periods')->cascadeOnDelete();
            $table->foreignUuid('alignment_okr_id')->nullable()->constrained('okrs')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->decimal('confidence_score', 4, 2)->default(0.00);
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['institution_id', 'period_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('okrs');
    }
};

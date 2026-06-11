<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('house_id')->constrained('houses')->cascadeOnDelete();
            $table->foreignUuid('resident_id')->constrained('residents')->cascadeOnDelete();
            $table->foreignUuid('fee_type_id')->constrained('fee_types')->cascadeOnDelete();
            $table->date('billing_month');
            $table->date('due_date');
            $table->enum('status', ['unpaid', 'paid', 'late']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};

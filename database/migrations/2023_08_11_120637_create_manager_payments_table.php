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
        Schema::create('manager_payments', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('doc', 255);
            $table->longText('file');
            $table->foreignId('manager_work_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('manager_cotization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('manager_bill_id')->nullable()->constrained()->nullOnDelete();
            $table->longText('descripcion');
            $table->unsignedInteger('total_price');
            $table->unsignedInteger('abono');
            $table->unsignedInteger('saldo');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_payments');
    }
};

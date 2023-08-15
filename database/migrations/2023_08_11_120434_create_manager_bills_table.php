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
        Schema::create('manager_bills', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('tipo', 100);
            $table->string('doc', 255)->unique();
            $table->unsignedInteger('total_price');
            $table->longText('file');
            $table->longText('descripcion');
            $table->foreignId('customer')->nullable()->constrained('manager_customers')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('manager_work_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('manager_cotization_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_bills');
    }
};

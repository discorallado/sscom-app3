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
        Schema::create('manager_cotization_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort');
            $table->unsignedInteger('cantidad');
            $table->unsignedInteger('precio_stock');
            $table->unsignedInteger('precio_anotado');
            $table->longText('descripcion');
            $table->foreignId('manager_cotization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('manager_product_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_cotization_items');
    }
};

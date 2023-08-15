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
      Schema::create('manager_category_product', function (Blueprint $table) {
        $table->id();
        $table->foreignId('manager_category_id')->nullable();
        $table->foreignId('manager_product_id')->nullable();
        $table->timestamps();
        // $table->primary(['manager_category_id', 'manager_product_id']);
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_category_product');
    }
};

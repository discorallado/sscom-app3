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
        Schema::create('manager_outdates', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 10);
            $table->string('tipo_doc', 191);
            $table->longText('file');
            $table->string('num_doc', 255);
            $table->date('date');
            $table->unsignedInteger('excento');
            $table->unsignedInteger('neto');
            $table->longText('observaciones');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('manager_customer_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_outdates');
    }
};

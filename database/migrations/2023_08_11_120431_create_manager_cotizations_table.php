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
        Schema::create('manager_cotizations', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 255)->unique();
            $table->date('fecha');
            $table->date('vencimiento')->nullable();
            $table->unsignedTinyInteger('validez');
            $table->longText('descripcion');
            $table->longText('file');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('manager_work_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('iva_price');
            $table->unsignedInteger('total_price');
            $table->enum('status', ['nueva', 'aprovada', 'terminada', 'vencida', 'cancelada'])->default('nueva');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_cotizations');
    }
};

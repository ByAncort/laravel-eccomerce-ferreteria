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
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('min_stock')->default(0)->comment('Alerta de stock mínimo');
            $table->string('location')->nullable()->comment('Bodega o ubicación');
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->enum('type', ['entrada', 'salida', 'ajuste']);
            $table->integer('quantity');
            $table->integer('stock_after')->comment('Stock resultante tras el movimiento');
            $table->string('reason')->nullable();
            $table->string('reference')->nullable()->comment('Nro. factura, orden, cotización');
            $table->string('source')->default('manual')->comment('manual | venta | cotizacion | orden_compra');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {        
        Schema::dropIfExists('stock');
        Schema::dropIfExists('stock_movements');
    }
};

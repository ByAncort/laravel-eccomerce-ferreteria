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
        Schema::create('items', function (Blueprint $table) {
            // Información básica del producto
            $table->string('code')->unique()->comment('Código interno o SKU');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Clasificación
            $table->string('category')->comment('Ej: Herramientas Eléctricas, Manuales, Seguridad');
            $table->string('subcategory')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();

            // Precios y stock
            $table->decimal('cost_price', 10, 2)->nullable()->comment('Precio de compra');
            $table->decimal('selling_price', 10, 2);
            $table->decimal('offer_price', 10, 2)->nullable();
            $table->timestamp('offer_ends_at')->nullable();

            // Especificaciones técnicas para ferretería
            $table->string('unit_measure')->default('unidad')->comment('unidad, metro, kg, litro');
            $table->decimal('weight', 8, 2)->nullable()->comment('Peso en kg');
            $table->string('dimensions')->nullable()->comment('Ej: 30x20x15 cm');
            $table->string('material')->nullable();

            // Media
            $table->string('main_image')->nullable();
            $table->json('gallery_images')->nullable();
            
            // Estado y visibilidad
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('featured')->default(false);
            $table->integer('views')->default(0);
            
            // Relación con proveedor (opcional)
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para búsquedas comunes
            $table->index(['category', 'status']);
            $table->index('brand');
            $table->index('code');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('category_id');
            $table->integer('price');
            $table->decimal('discount');
            $table->decimal('rating');
            $table->string('brand');
            $table->integer('member_id');
            $table->timestamps();
            $table->enum('deleted', ['0', '1']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

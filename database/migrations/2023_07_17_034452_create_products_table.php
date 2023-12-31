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
            $table->text('description');
            $table->integer('price');
            $table->decimal('discount', 8, 2);
            $table->decimal('rating',3,2);
            $table->string('brand');
            $table->integer('member_id');
            $table->timestamps();
            $table->softDeletes();
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
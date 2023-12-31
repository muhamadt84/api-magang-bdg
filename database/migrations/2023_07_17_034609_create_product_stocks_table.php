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
        Schema::dropIfExists('product_stocks');
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->text('qty');
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
        Schema::dropIfExists('product_stocks');
    }
};

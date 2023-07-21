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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title',100);
            $table->text('description');
            $table->integer('categori_id');
            $table->integer('total_like');
            $table->integer('total_comment');
            $table->integer('member_id');
            $table->timestamps();
            $table->enum('deleted', ['0', '1']);
            $table->softDeletes();
            // $table->foreign('categori_id')->references('id')->on('table_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

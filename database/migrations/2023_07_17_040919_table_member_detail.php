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
        Schema::create('table_member_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob');
            $table->enum('gender', ['1', '2']);
            $table->text('address');
            $table->text('image');
            $table->text('bio');
            $table->string('high_school');
            $table->mediumInteger('phone_number');
            $table->rememberToken();
            $table->timestamps();
            $table->enum('deleted_at', ['0', '1']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

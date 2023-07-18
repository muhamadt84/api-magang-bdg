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
        Schema::create('table_member', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
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

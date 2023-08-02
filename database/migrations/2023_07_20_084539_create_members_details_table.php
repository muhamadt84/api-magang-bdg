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
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->string('image')->nullable();
            $table->text('bio')->nullable();
            $table->string('highschool')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('table_member_detail', function (Blueprint $table) {
        //     $table->dropColumn([
        //         'first_name',
        //         'last_name',
        //         'dob',
        //         'gender',
        //         'address',
        //         'image',
        //         'bio',
        //         'highschool',
        //         'phone_number',
        //     ]);
        // });

        // Schema::rename('table_member_detail', 'members'); // Revert the table name back to 'members'
        Schema::dropIfExists('table_member_detail');
    }

};

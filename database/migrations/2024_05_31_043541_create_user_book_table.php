<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');  // Define the column before setting foreign key
            $table->unsignedBigInteger('book_id');  // Define the column before setting foreign key
            $table->enum('status', ['Available', 'Onloan', 'Deleted'])->default('Available');
            $table->dateTime('applied_date')->default(now());
            $table->timestamps();

            // Setting foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');

            $table->unique('book_id'); // Ensures each book is only lent to one user at a time
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_book');

    }
};

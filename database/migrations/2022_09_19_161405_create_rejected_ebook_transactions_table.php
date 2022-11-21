<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRejectedEbookTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rejected_ebook_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('book_title');
            $table->year('year');
            $table->string('month');
            $table->string('class_of_trade');
            $table->string('line_item_no');
            $table->integer('quantity');
            $table->double('price');
            $table->double('proceeds');
            $table->double('royalty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rejected_ebook_transactions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRejectedPodTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rejected_pod_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('book_title');
            $table->string('instance_id');
            $table->string('isbn');
            $table->string('market');
            $table->year('year');
            $table->string('month');
            $table->string('flag');
            $table->string('status')->nullable();
            $table->string('format');
            $table->integer('quantity');
            $table->double('price');
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
        Schema::dropIfExists('rejected_pod_transactions');
    }
}

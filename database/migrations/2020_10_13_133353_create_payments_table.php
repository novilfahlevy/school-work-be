<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->integer('payment_number');
            $table->tinyInteger('status')->comment('0 = belum lunas, 1 = lunas');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('loans')->cascadeOnDelete();
            $table->foreign('employee_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

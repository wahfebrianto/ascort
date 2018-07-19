<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardOfDirectorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('board_of_director', function (Blueprint $table) {
            $table->increments('id');
            $table->text('bod_name');
            $table->text('address');
            $table->string('state', 50);
            $table->string('city', 50);
            $table->string('zipcode', 7);
            $table->string('phone_number', 20);
            $table->string('email', 100);
            $table->string('type',15);
            $table->string('identity_number', 50);
            $table->string('NPWP', 30);
            $table->string('position', 100);
            $table->string('bank', 150);
            $table->string('bank_branch', 150);
            $table->string('account_name', 150);
            $table->string('account_number', 50);
            $table->tinyInteger('is_active')->default(1); // 1/0
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
        //
    }
}

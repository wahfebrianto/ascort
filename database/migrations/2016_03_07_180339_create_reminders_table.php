<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('reminder_for', 20); // owner / admin
            $table->date('start_date');
            $table->date('end_date');
            $table->string('subject', 100);
            $table->text('content');
            $table->text('respond_link'); // "#" if there's no respond link
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
        Schema::drop('reminders');
    }
}

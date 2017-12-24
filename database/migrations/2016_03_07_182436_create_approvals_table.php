<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('subject');
            $table->text('description');
            $table->tinyInteger('is_approved')->default(0); // 1/0
            $table->timestamps();

            $table->foreign('user_id', 'approvals_user_fk')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropForeign('approvals_user_fk');
        });
        Schema::drop('approvals');
    }
}

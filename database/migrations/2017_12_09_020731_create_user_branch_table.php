<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('branch_office_user', function (Blueprint $table) {
          $table->unsignedInteger('user_id');
          $table->unsignedInteger('branch_office_id');

          $table->foreign('user_id')->references('id')->on('users')
              ->onUpdate('cascade')->onDelete('cascade');
          $table->foreign('branch_office_id')->references('id')->on('branch_offices')
              ->onUpdate('cascade')->onDelete('cascade');

          $table->primary(['user_id', 'branch_office_id']);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('branch_users');
    }
}

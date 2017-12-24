<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchOfficeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('branch_offices', function (Blueprint $table) {
          $table->increments('id');
          $table->string('branch_name', 50);

          $table->text('address');
          $table->string('state', 50);
          $table->string('city', 50);
          $table->string('zipcode', 7);
          $table->string('phone_number', 20);

          $table->tinyInteger('is_active')->default(1); // 1/0
          $table->timestamps();

          $table->unique(['branch_name']);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('branch_offices');
    }
}

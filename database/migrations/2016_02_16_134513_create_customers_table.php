<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('NIK', 20);
            $table->string('name', 200);
            $table->string('phone_number', 20);
            $table->string('handphone_number', 20);
            $table->string('email', 100);

            $table->string('gender', 1); // M / F
            $table->text('address');
            $table->string('state', 50);
            $table->string('city', 50);
            $table->string('zipcode', 7);

            $table->text('cor_address');
            $table->string('cor_state', 50);
            $table->string('cor_city', 50);
            $table->string('cor_zipcode', 7);


            $table->integer('branch_office_id')->unsigned();

            $table->timestamp('last_transaction');
            $table->tinyInteger('is_active')->default(1); // 1/0
            $table->timestamps();

            $table->unique(['NIK']);
            $table->foreign('branch_office_id', 'branch_office_fk')->references('id')->on('branch_offices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('customers', function (Blueprint $table) {
          $table->dropForeign('branch_office_fk');
      });
        Schema::drop('customers');
    }
}

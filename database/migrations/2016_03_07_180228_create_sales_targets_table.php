<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_position_id')->unsigned();
            $table->bigInteger('target_amount');
            $table->timestamps();

            $table->foreign('agent_position_id', 'targets_position_fk')->references('id')->on('agent_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales_targets', function (Blueprint $table) {
            $table->dropForeign('targets_position_fk');
        });
        Schema::drop('sales_targets');
    }
}

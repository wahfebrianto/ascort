<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleCommissionPercentagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_commission_percentages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_position_id')->unsigned();
            $table->float('percentage');
            $table->tinyInteger('is_active')->default(1); // 1/0
            $table->timestamps();

            $table->foreign('agent_position_id', 'sale_commission_position_fk')->references('id')->on('agent_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_commission_percentages', function (Blueprint $table) {
            $table->dropForeign('sale_commission_position_fk');
        });
        Schema::drop('sale_commission_percentages');
    }
}

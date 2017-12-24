<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOverridingCommissionPercentages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overriding_commission_percentages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_position_id')->unsigned();
            $table->integer('override_from')->unsigned()->nullable();
            $table->integer('level')->default(0); // 0 = no level
            $table->float('percentage');
            $table->tinyInteger('is_active')->default(1); // 1/0
            $table->timestamps();

            $table->foreign('agent_position_id', 'ovr_commission_position_fk')->references('id')->on('agent_positions')->onDelete('cascade');
            $table->foreign('override_from', 'ovr_commission_from_fk')->references('id')->on('agent_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overriding_commission_percentages', function (Blueprint $table) {
            $table->dropForeign('ovr_commission_position_fk');
            $table->dropForeign('ovr_commission_from_fk');
        });
        Schema::drop('overriding_commission_percentages');
    }
}

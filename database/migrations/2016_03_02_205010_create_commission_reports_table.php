<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_id')->unsigned(); //FK
            $table->string('type', 30); // commission, overriding, top overriding
            $table->dateTime('process_date');
            $table->integer('period'); // 1 / 2
            $table->date('month_year'); // formatted date only for month & year
            $table->double('total_nominal');
            $table->double('total_FYP');
            $table->double('total_commission');
            $table->double('commission_hold');
            $table->double('minus');
            $table->double('cuts');
            $table->double('tax_adjustment');
            $table->double('gross_commission');
            $table->double('current_YTD');
            $table->double('last_YTD');
            $table->double('tax');
            $table->double('nett_commission');
            $table->text('data');
            $table->timestamps();

            $table->foreign('agent_id', 'comm_reports_agent_fk')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commission_reports', function (Blueprint $table) {
            $table->dropForeign('comm_reports_agent_fk');
        });
        Schema::drop('commission_reports');
    }
}

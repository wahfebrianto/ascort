<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_id')->unsigned(); //FK
            $table->dateTime('process_date');
            $table->integer('period'); // 1 / 2
            $table->date('month_year'); // formatted date only for month & year
            $table->double('nominal');
            $table->double('last_nominal_accumulation'); // this year's nominal accumulation
            $table->double('discounted'); // discounted current accumulation = (nominal + nominal_accumulation) * 50%
            $table->double('last_discounted_accumulation');
            $table->double('taxable'); // discounted - discounted_accumulation
            $table->double('last_taxable_accumulation');
            $table->double('tax');
            $table->double('nett_commission'); // nominal - tax
            $table->text('data');
            $table->timestamps();

            $table->foreign('agent_id', 'tax_reports_agent_fk')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tax_reports');
    }
}

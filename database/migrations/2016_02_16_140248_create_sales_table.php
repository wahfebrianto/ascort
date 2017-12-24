<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_id')->unsigned()->nullable();
            $table->float('agent_commission'); //the current commission percentage of the agent
            $table->integer('product_id')->unsigned()->nullable();
            $table->string('number', 50);

            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('customer_name', 200);

            $table->integer('tenor');
            $table->integer('MGI');
            $table->integer('MGI_month')->default(0);
            $table->date('MGI_start_date');
            $table->bigInteger('nominal'); // Rp
            $table->bigInteger('interest')->default(6); // %
            $table->bigInteger('additional');

            $table->string('bank', 150);
            $table->string('bank_branch', 150);
            $table->string('account_name', 150);
            $table->string('account_number', 50);

            $table->integer('branch_office_id')->unsigned();
            $table->tinyInteger('is_active')->default(1); // 1/0
            $table->timestamps();

            $table->foreign('product_id', 'sales_product_fk')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('agent_id', 'sales_agent_fk')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('customer_id', 'sales_customer_fk')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('branch_office_id', 'sales_branch_office_fk')->references('id')->on('branch_offices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_product_fk');
            $table->dropForeign('sales_agent_fk');
            $table->dropForeign('sales_customer_fk');
            $table->dropForeign('sales_branch_office_fk');
        });
        Schema::drop('sales');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('agent_code', 9);
            $table->integer('parent_id')->unsigned()->nullable();

            $table->string('NIK', 20);
            $table->string('name', 200);
            $table->string('birth_place', 50);
            $table->date('DOB');

            $table->string('gender', 1); // M / F
            $table->text('address');
            $table->string('state', 50);
            $table->string('city', 50);
            $table->string('zipcode', 7);
            $table->string('phone_number', 20);
            $table->string('handphone_number', 20);
            $table->string('email', 100);

            $table->integer('agent_position_id')->unsigned();
            $table->date('join_date');

            $table->string('NPWP', 30);
            $table->string('bank', 150);
            $table->string('bank_branch', 150);
            $table->string('account_name', 150);
            $table->string('account_number', 50);

            $table->integer('branch_office_id')->unsigned();
            $table->tinyInteger('is_active')->default(1); // 1/0
            $table->timestamps();

            $table->unique(['NIK', 'NPWP', 'agent_code']);

            $table->foreign('agent_position_id', 'agent_position_fk')->references('id')->on('agent_positions')->onDelete('cascade');
            $table->foreign('branch_office_id', 'agent_branch_office_fk')->references('id')->on('branch_offices')->onDelete('cascade');
            $table->foreign('parent_id', 'agent_parent_fk')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropForeign('agent_position_fk');
            $table->dropForeign('agent_branch_office_fk');
        });
        Schema::drop('agents');
    }
}

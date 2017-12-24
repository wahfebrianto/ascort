<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('acronym', 10);
            $table->string('name', 100);
            $table->integer('level')->unsigned();
            $table->tinyInteger('is_active')->default(1); // 1/0
            $table->timestamps();

            $table->foreign('parent_id', 'agentpos_parent_fk')->references('id')->on('agent_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_positions', function (Blueprint $table) {
            $table->dropForeign('agentpos_parent_fk');
        });
        Schema::drop('agent_positions');
    }
}

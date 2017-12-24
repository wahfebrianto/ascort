<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER INSERT_AGENTS
        AFTER INSERT ON agents
        FOR EACH ROW
        BEGIN
            INSERT INTO agent_position_histories(agent_id, agent_position_id) values(NEW.id, NEW.agent_position_id);
        END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `INSERT_AGENTS`');
    }
}

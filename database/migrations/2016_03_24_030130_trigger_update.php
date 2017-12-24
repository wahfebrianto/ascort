<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER UPDATE_AGENTS
        AFTER UPDATE ON agents
        FOR EACH ROW
        BEGIN
            IF NEW.agent_position_id != OLD.agent_position_id THEN
                INSERT INTO agent_position_histories(agent_id, agent_position_id) values(NEW.id, NEW.agent_position_id);
            END IF;
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
        DB::unprepared('DROP TRIGGER `UPDATE_AGENTS`');
    }
}

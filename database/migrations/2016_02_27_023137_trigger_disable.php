<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TriggerDisable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER DISABLE_AGENT_POSITIONS
        AFTER UPDATE ON agent_positions
        FOR EACH ROW
        BEGIN
            IF NEW.is_active = 0 THEN
                UPDATE agents SET is_active = 0 WHERE agent_position_id = OLD.id;
                UPDATE sale_commission_percentages SET is_active = 0 WHERE agent_position_id = OLD.id;
                UPDATE overriding_commission_percentages SET is_active = 0 WHERE agent_position_id = OLD.id;
            END IF;
        END
        ');

        DB::unprepared('
        CREATE TRIGGER DISABLE_CUSTOMERS
        AFTER UPDATE ON customers
        FOR EACH ROW
        BEGIN
            UPDATE sales SET customer_id = null WHERE customer_id = OLD.id;
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
        DB::unprepared('DROP TRIGGER `DISABLE_AGENT_POSITIONS`');
        DB::unprepared('DROP TRIGGER `DISABLE_CUSTOMERS`');
    }
}

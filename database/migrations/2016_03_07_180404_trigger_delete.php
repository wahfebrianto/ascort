<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER DELETE_CUSTOMERS
        BEFORE DELETE ON customers
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
        DB::unprepared('DROP TRIGGER `DELETE_CUSTOMERS`');
    }
}

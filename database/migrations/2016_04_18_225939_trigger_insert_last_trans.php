<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerInsertLastTrans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER INSERT_LAST_TRANS
        BEFORE INSERT ON customers
        FOR EACH ROW
        BEGIN
             SET NEW.last_transaction = now();
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
        DB::unprepared('DROP TRIGGER `INSERT_LAST_TRANS`');
    }
}

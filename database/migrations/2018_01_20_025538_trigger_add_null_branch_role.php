<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerAddNullBranchRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER INSERT_ROLES
		BEFORE INSERT ON roles 
		FOR EACH ROW 
			IF NEW.branch_office_id = 0 THEN 
				SET NEW.branch_office_id = NULL; 
			END IF 
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `INSERT_ROLES`');
    }
}

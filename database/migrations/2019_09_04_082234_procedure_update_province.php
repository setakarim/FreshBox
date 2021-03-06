<?php

use Illuminate\Database\Migrations\Migration;

class ProcedureUpdateProvince extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS update_province');

        DB::unprepared('CREATE PROCEDURE update_province(IN v_id INT, IN name VARCHAR(100), IN updated_by INT )
        BEGIN
        UPDATE master_province SET name = name, updated_by = updated_by, updated_at = now() WHERE id = v_id;
        END');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS update_province');
    }
}

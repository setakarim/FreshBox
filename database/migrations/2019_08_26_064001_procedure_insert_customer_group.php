<?php

use Illuminate\Database\Migrations\Migration;

class ProcedureInsertCustomerGroup extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS insert_customer_group');

        DB::unprepared('CREATE PROCEDURE insert_customer_group( IN name VARCHAR(191), IN description VARCHAR(191), IN created_by INT )
        BEGIN
        INSERT INTO master_customer_group (name, description, created_at, created_by) VALUES (name, description, now(), created_by);
        END');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS insert_customer_group');
    }
}

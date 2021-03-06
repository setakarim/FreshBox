<?php

use Illuminate\Database\Migrations\Migration;

class ProcedureInsertCustomerType extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS insert_customer_type');

        DB::unprepared('CREATE PROCEDURE insert_customer_type( IN name VARCHAR(191), IN description VARCHAR(191), IN created_by INT )
        BEGIN
        INSERT INTO master_customer_type (name, description, created_at, created_by) VALUES (name, description, now(), created_by);
        END');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS insert_customer_type');
    }
}

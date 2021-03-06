<?php

use Illuminate\Database\Migrations\Migration;

class ProcedureInsertSalesOrder extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS insert_sales_order');

        DB::unprepared('CREATE PROCEDURE insert_sales_order( IN sales_order_no VARCHAR(20), IN source_order_id INT, IN customer_id INT, IN fulfillment_date DATE, IN remarks VARCHAR(191), IN status INT, IN file BINARY, IN driver_id INT, IN created_by INT )
        BEGIN
        INSERT INTO trx_sales_order (sales_order_no, source_order_id, customer_id, fulfillment_date, remarks, status, file, driver_id, created_at, created_by) VALUES (sales_order_no, source_order_id, customer_id, fulfillment_date, remarks, status, file, driver_id, now(), created_by);
        END');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS insert_sales_order');
    }
}

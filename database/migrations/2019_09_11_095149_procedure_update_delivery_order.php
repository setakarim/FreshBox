<?php

use Illuminate\Database\Migrations\Migration;

class ProcedureUpdateDeliveryOrder extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS update_delivery_order');

        DB::unprepared('CREATE PROCEDURE update_delivery_order(IN v_id INT, IN delivery_order_no VARCHAR(15), IN sales_order_id INT, IN customer_id INT, IN do_date DATE, IN confirm_date DATE, IN driver_id INT, IN updated_by INT )
        BEGIN
        UPDATE trx_delivery_order SET delivery_order_no = delivery_order_no, sales_order_id = sales_order_id, customer_id = customer_id, do_date = do_date, confirm_date = confirm_date, driver_id = driver_id, updated_by = updated_by, updated_at = now() WHERE id = v_id;
        END');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS update_delivery_order');
    }
}

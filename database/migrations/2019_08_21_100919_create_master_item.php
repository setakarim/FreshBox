<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterItem extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('master_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('skuid');
            $table->string('name_item');
            $table->string('name_item_b2c')->nullable();
            $table->string('name_item_latin')->nullable();
            $table->string('description')->nullable();
            $table->string('is_trf_item')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->unsignedBigInteger('origin_id')->nullable();
            $table->float('tax')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['skuid', 'name_item', 'tax']);

            $table->foreign('category_id')->on('master_category')->references('id')->onDelete('cascade');
            $table->foreign('uom_id')->on('master_uom')->references('id')->onDelete('cascade');
            $table->foreign('origin_id')->on('master_origin')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('master_item');
    }
}

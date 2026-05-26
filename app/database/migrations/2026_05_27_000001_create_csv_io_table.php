<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsvIoTable extends Migration
{
    public function up()
    {
        Schema::create('csv_io', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('target_type', 30);
            $table->string('file_name_rule', 255)->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('csv_io');
    }
}

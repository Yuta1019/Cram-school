<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiriesTable extends Migration
{
    public function up()
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('parent_name', 100);
            $table->string('parent_phone', 20)->nullable();
            $table->string('parent_email', 255)->nullable();
            $table->string('preferred_contact_method', 20)->nullable();
            $table->string('student_name', 100);
            $table->string('school_name', 100)->nullable();
            $table->string('grade', 20)->nullable();
            $table->string('desired_course_name', 100)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedBigInteger('assigned_user_id')->nullable();
            $table->text('inquiry_content')->nullable();
            $table->text('memo')->nullable();
            $table->dateTime('last_contact_at')->nullable();
            $table->timestamps();

            $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inquiries');
    }
}

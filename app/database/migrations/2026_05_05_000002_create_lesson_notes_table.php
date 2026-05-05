<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonNotesTable extends Migration
{
    public function up()
    {
        Schema::create('lesson_notes', function (Blueprint $table) {
            // 所感ID（主キー）
            $table->bigIncrements('id');

            // 問い合わせID（inquiries テーブルを参照）
            $table->unsignedBigInteger('inquiry_id');
            $table->foreign('inquiry_id')
                  ->references('id')
                  ->on('inquiries')
                  ->onDelete('cascade');

            // コース名（空でもOK）
            $table->string('course_name', 100)->nullable();

            // 授業日
            $table->date('lesson_date');

            // 理解度（0:良い, 1:普通, 2:要支援）
            $table->tinyInteger('understanding_level')->nullable();

            // 集中度（0:良い, 1:普通, 2:要支援）
            $table->tinyInteger('concentration_level')->nullable();

            // 今日やった内容（空でもOK）
            $table->text('lesson_summary')->nullable();

            // 保護者向けコメント（空でもOK）
            $table->text('parent_comment')->nullable();

            // 講師メモ（空でもOK）
            $table->text('teacher_note')->nullable();

            // 登録者ID（users テーブルを参照）
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // 作成日時・更新日時（自動で入る）
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_notes');
    }
}

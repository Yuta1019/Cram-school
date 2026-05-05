<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('contact_histories', function (Blueprint $table) {
            $table->bigIncrements('id');

            // どの問い合わせの連絡履歴か（問い合わせが削除されたら一緒に消える）
            $table->unsignedBigInteger('inquiry_id');
            $table->foreign('inquiry_id')->references('id')->on('inquiries')->onDelete('cascade');

            // 連絡手段（電話、メールなど）
            $table->string('contact_method', 20)->nullable();

            // 対応した担当者（担当者が削除されても履歴は残す）
            $table->unsignedBigInteger('contacted_by')->nullable();
            $table->foreign('contacted_by')->references('id')->on('users')->onDelete('set null');

            // 対応日時
            $table->dateTime('contacted_at')->nullable();

            // 連絡内容
            $table->text('content')->nullable();

            // 対応結果（0:返信待ち, 1:対応完了）
            $table->tinyInteger('response_status')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_histories');
    }
}

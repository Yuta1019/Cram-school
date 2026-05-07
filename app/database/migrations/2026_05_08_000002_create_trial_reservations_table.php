<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrialReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('trial_reservations', function (Blueprint $table) {

            // 予約ID（主キー・自動で番号が振られる）
            $table->bigIncrements('id');

            // 問い合わせID（inquiries テーブルを参照）
            $table->unsignedBigInteger('inquiry_id');
            $table->foreign('inquiry_id')
                  ->references('id')
                  ->on('inquiries')
                  ->onDelete('cascade');

            // 体験会ID（trial_events テーブルを参照）
            $table->unsignedBigInteger('trial_event_id');
            $table->foreign('trial_event_id')
                  ->references('id')
                  ->on('trial_events')
                  ->onDelete('cascade');

            // 予約状態
            $table->string('reservation_status', 20);

            // 予約日時
            $table->datetime('reserved_at');

            // 受付日時（空でもOK）
            $table->datetime('checked_in_at')->nullable();

            // 出席状態（空でもOK）
            $table->string('attendance_status', 20)->nullable();

            // 備考（空でもOK）
            $table->text('note')->nullable();

            // 作成者ID（users テーブルを参照・作成者が削除されたら null にする）
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
        Schema::dropIfExists('trial_reservations');
    }
}

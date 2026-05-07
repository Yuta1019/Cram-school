<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrialEventsTable extends Migration
{
    public function up()
    {
        Schema::create('trial_events', function (Blueprint $table) {

            // 体験会ID（主キー・自動で番号が振られる）
            $table->bigIncrements('id');

            // 開催日
            $table->date('event_date');

            // 開始時間・終了時間
            $table->time('start_time');
            $table->time('end_time');

            // コース名
            $table->string('course_name', 100);

            // 定員（デフォルト0）
            $table->integer('capacity')->default(0);

            // 予約数（デフォルト0）
            $table->integer('reserved_count')->default(0);

            // 会場（空でもOK）
            $table->string('venue', 100)->nullable();

            // 状態（0:空きあり, 1:満員）空でもOK
            $table->tinyInteger('status')->default(0)->nullable();

            // 登録者ID（users テーブルを参照・登録者が削除されたら null にする）
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
        Schema::dropIfExists('trial_events');
    }
}

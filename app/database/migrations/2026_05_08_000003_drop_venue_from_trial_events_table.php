<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropVenueFromTrialEventsTable extends Migration
{
    // venue カラムを削除する
    public function up()
    {
        Schema::table('trial_events', function (Blueprint $table) {
            $table->dropColumn('venue');
        });
    }

    // 元に戻すときは venue カラムを追加する
    public function down()
    {
        Schema::table('trial_events', function (Blueprint $table) {
            $table->string('venue', 100)->nullable();
        });
    }
}

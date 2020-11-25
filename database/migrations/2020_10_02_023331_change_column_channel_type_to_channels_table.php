<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnChannelTypeToChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channels', function (Blueprint $table) {
            if (Schema::hasColumn('channels', 'channels_type')) {
                $table->renameColumn('channels_type', 'channel_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channels', function (Blueprint $table) {
            if (Schema::hasColumn('channels', 'channel_type')) {
                $table->renameColumn('channel_type', 'channels_type');
            }
        });
    }
}

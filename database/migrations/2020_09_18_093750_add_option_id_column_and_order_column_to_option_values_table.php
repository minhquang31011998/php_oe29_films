<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOptionIdColumnAndOrderColumnToOptionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_values', function (Blueprint $table) {
            if (!Schema::hasColumn('option_values', 'option_id')) {
                $table->integer('option_id');
                $table->integer('order');
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
        Schema::table('option_values', function (Blueprint $table) {
            if (Schema::hasColumn('option_values', 'option_id')) {
                $table->dropColumn('option_id');
                $table->dropColumn('order');
            }
        });
    }
}

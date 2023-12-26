<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddIsPinnedColumnToPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('posts', 'pinned')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->boolean('pinned')->default(false)->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('posts', 'pinned')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('pinned');
            });
        }
    }
}

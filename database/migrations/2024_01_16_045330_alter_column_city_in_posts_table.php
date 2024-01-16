<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnCityInPostsTable extends Migration
{

    public function up()
    {
        if (Schema::hasColumn('posts', 'city')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('city')->nullable()->change();
            });
        }
    }


    public function down()
    {

    }
}

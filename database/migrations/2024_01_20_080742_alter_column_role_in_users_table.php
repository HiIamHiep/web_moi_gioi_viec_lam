<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnRoleInUsersTable extends Migration
{

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->integer('role')->change();
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->integer('role')->default(null)->change();
            }
        });
    }

    public function down()
    {

    }
}

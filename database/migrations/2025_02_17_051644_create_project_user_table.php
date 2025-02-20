<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectUserTable extends Migration
{
    public function up()
    {
        Schema::table('project_user', function (Blueprint $table) {
            // Mengganti nama kolom yang salah
            $table->renameColumn('project_management_id', 'project_id');
        });
    }

    public function down()
    {
        Schema::table('project_user', function (Blueprint $table) {
            // Kembalikan ke kolom semula jika rollback
            $table->renameColumn('project_id', 'project_management_id');
        });
    }
}

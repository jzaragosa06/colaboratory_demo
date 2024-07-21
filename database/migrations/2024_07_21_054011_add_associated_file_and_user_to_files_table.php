<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssociatedFileAndUserToFilesTable extends Migration
{
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->string('associated_file_path')->nullable();
            $table->unsignedBigInteger('associated_by')->nullable();
            $table->foreign('associated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['associated_by']);
            $table->dropColumn('associated_file_path');
            $table->dropColumn('associated_by');
        });
    }
}
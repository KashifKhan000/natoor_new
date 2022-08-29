<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record_notes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('item_id')->nullable();
            $table->string('item_type',190)->nullable();
            $table->string('file_path',190)->nullable();
            $table->string('file_alias',190)->nullable();
            $table->string('file_name',190)->nullable();
            $table->string('file_ext',190)->nullable();
            $table->string('file_type',190)->nullable();
            $table->string('file_mime',190)->nullable();
            $table->string('file_size',190)->nullable();
            $table->string('file_caption',190)->nullable();
            $table->text('file_detail')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_files');
    }
}

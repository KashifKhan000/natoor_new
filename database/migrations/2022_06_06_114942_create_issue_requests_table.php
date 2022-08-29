<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssueRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('priority')->nullable();
            $table->unsignedBigInteger('raised_by')->nullable();
            $table->unsignedBigInteger('building_id')->nullable();
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->string('dns',190)->nullable();
            $table->longText('description')->nullable();
            $table->enum('status',['Initial','Processed','RequestCompleted','Resolved']);
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
        Schema::dropIfExists('issue_requests');
    }
}

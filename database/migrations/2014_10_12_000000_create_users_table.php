<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',190)->nullable();
            $table->string('last_name',190)->nullable();
            $table->string('username',190)->nullable();
            $table->string('email',190)->unique();
            $table->integer('type')->nullable()->comment("0 => SuperAdmin, 1 => Admin, 2 => Customers");
            $table->longText('fcm_token')->nullable();
            $table->string('gender',11)->nullable();
            $table->date('dob')->nullable();
            $table->string('mobile_number',190)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',190)->nullable();
            $table->rememberToken();
            $table->enum('status',['Active','Inactive'])->nullable();
            $table->string('company_name',190)->nullable();
            $table->string('dns',190)->nullable();
            $table->string('company_identifier',190)->nullable();
            $table->unsignedBigInteger('country_id')->default(0);
            $table->unsignedBigInteger('city_id')->default(0);
            $table->string('address',190)->nullable();
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
        Schema::dropIfExists('users');
    }
}

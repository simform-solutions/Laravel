<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('first_name', 30)->index();
            $table->string('last_name', 30)->index();
            $table->string('email', 100)->unique()->index();
            $table->string('mobile_number', 20)->unique()->index()->nullable();
            $table->string('avatar')->nullable()->unique();
            $table->string('password')->nullable();
            $table->unsignedBigInteger('facebook_id')->nullable()->unique()->index();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
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

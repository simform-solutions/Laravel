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
            $table->string('remember_token')->nullable()->index();
            $table->enum('device_type', [0, 1])->default(0)->index();
            $table->string('push_token')->nullable()->index();
            $table->string('time_zone', 10)->index()->nullable();
            $table->timestamp('last_login_at')->nullable()->index();
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

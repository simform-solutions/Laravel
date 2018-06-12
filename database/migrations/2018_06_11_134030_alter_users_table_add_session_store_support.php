<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTableAddSessionStoreSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('device_type', [0, 1])->default(0)->index();
            $table->string('push_token')->nullable()->index();
            $table->string('time_zone', 10)->index()->nullable();
            $table->timestamp('last_login_at')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['device_type', 'push_token', 'time_zone', 'last_login_at']);
        });
    }
}

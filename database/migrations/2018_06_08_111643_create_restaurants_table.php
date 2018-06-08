<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30)->index();
            $table->text('address');
            $table->unsignedInteger('manager_id')->index();
            $table->foreign('manager_id')->references('id')->on('users');
            $table->string('phone', 20)->nullable()->index()->unique();
            $table->string('email', 100)->nullable()->index()->unique();
            $table->float('avg_ratings', 10, 2)->default(0)->index();
            $table->string('photo')->nullable();
            $table->point('location')->nullable()->index();
            $table->text('description')->nullable();
            $table->enum('price_range', [1, 2, 3])->default(1)->index();
            $table->string('time_zone', '10')->index()->nullable();
            $table->boolean('is_active')->default(true)->index();
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
        Schema::dropIfExists('restaurants');
    }
}

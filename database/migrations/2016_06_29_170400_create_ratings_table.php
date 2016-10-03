<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('ratings', function(Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->integer('rating_plus')->default(0);
            $table->integer('rating_minus')->default(0);
            $table->bigInteger('summary')->default(0);
            $table->string('rem')->default('');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('ratings');
    }

}

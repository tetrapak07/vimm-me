<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('members', function(Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('ip', 255)->default('');
            $table->smallInteger('questions_restrict')->default(3);
            $table->dateTime('questions_restrict_day')->nullable();
            $table->smallInteger('answers_restrict')->default(30);
            $table->dateTime('answers_restrict_day')->nullable();
            $table->boolean('restrict_flag')->default(1);
            $table->boolean('visible')->default(1);
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
        Schema::drop('members');
    }

}

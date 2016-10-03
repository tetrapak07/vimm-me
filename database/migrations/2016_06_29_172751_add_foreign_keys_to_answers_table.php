<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToAnswersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('answers', function(Blueprint $table) {
            $table->foreign('question_id', 'fk_answers_1')->references('id')->on('questions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('member_id', 'fk_answers_2')->references('id')->on('members')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('answers', function(Blueprint $table) {
            $table->dropForeign('fk_answers_1');
            $table->dropForeign('fk_answers_2');
        });
    }

}

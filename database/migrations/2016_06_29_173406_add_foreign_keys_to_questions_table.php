<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToQuestionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('questions', function(Blueprint $table) {
            $table->foreign('member_id', 'fk_questions_1')->references('id')->on('members')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('questions', function(Blueprint $table) {
            $table->dropForeign('fk_questions_1');
        });
    }

}

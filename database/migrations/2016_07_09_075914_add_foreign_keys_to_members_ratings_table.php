<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToMembersRatingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('members_ratings', function(Blueprint $table) {
            $table->foreign('member_id', 'fk_members_ratings_1')->references('id')->on('members')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('rating_id', 'fk_members_ratings_2')->references('id')->on('ratings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('members_ratings', function(Blueprint $table) {
            $table->dropForeign('fk_members_ratings_1');
            $table->dropForeign('fk_members_ratings_2');
        });
    }

}

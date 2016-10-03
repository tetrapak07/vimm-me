<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersRatingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('members_ratings', function(Blueprint $table) {
            $table->bigInteger('member_id')->unsigned()->index('fk_members_ratings_1_idx');
            $table->bigInteger('rating_id')->unsigned()->index('fk_members_ratings_2_idx');
            $table->primary(['member_id', 'rating_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('members_ratings');
    }

}

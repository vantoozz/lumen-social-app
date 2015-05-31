<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->enum('provider', array('fb', 'vk', 'ok', 'mm'))->nullable()->default(null);
                $table->bigInteger('provider_id')->unsigned()->nullable()->default(null);
                $table->string('first_name', 128)->nullable()->default(null);
                $table->string('last_name', 128)->nullable()->default(null);
                $table->string('photo', 256)->nullable()->default(null);
                $table->string('cdn_photo', 256)->nullable()->default(null);
                $table->enum('sex', array('male', 'female'))->nullable()->default(null);
                $table->date('birth_date')->nullable()->default(null);
                $table->timestamp('last_sync_at')->nullable()->default(null);
                $table->timestamp('last_login_at')->nullable()->default(null);
                $table->timestamps();
                $table->unique(array('provider', 'provider_id'));
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }

}

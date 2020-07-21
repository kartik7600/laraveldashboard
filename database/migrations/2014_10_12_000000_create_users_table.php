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
            $table->increments('uid');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('user_first_name');
            $table->string('user_last_name');
            $table->date('user_dob');
            $table->string('user_mobile', 20);
            $table->string('user_phone', 20);
            $table->text('user_address');
            $table->string('user_profile_image');
            $table->string('user_role');
            $table->string('user_status');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('reset_password_token');
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

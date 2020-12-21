<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialTwitchAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_twitch_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->text('email');
            $table->text('display_name');
            $table->text('token');
            $table->text('refreshToken');
            $table->text('type')->nullable();
            $table->text('broadcaster_type')->nullable();
            $table->longText('description')->nullable();
            $table->text('profile_image_url')->nullable();
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
        Schema::dropIfExists('social_twitch_accounts');
    }
}

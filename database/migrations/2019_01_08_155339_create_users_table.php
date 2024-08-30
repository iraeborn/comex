<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			
			$table->string('name')->index();
			$table->string('email')->index()->unique();
			$table->string('username')->index()->unique();
			
			$table->string('password')->nullable();
			$table->string('profile_picture')->nullable();

			$table->string('country')->nullable();
			$table->string('neighborhood')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('zip')->nullable();
			$table->string('number')->nullable();
			$table->string('address_1')->nullable();
			$table->string('address_2')->nullable();

			$table->string('token')->nullable();
			$table->string('api_token')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}
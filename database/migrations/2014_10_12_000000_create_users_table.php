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
            $table->bigIncrements('id');
            $table->integer('school_id')->nullable();
            $table->integer('school_creator_id')->nullable();
            $table->integer('parent_id')->default(0);
            $table->integer('instructor_id')->default(0);
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email', 50)->unique();
            $table->string('surname', 50)->nullable();
            $table->string('postcode', 20)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('type', 20);
            $table->date('dob')->nullable();
            $table->string('image')->nullable();
            $table->boolean('payment_verified')->nullable()->default(0);
            $table->boolean('email_verified')->nullable()->default(0);
            $table->boolean('is_verified')->nullable()->default(0);
            $table->string('verification_token')->nullable();
            $table->rememberToken();
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

<?php

use Silber\Bouncer\Database\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBouncerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->abilities(), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('entity_id')->unsigned()->nullable();
            $table->string('entity_type')->nullable();
            $table->timestamps();

            $table->unique(['name', 'entity_id', 'entity_type']);
        });

        Schema::create($this->roles(), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->unique(['role_id', 'user_id']);

            $table->foreign('role_id')->references('id')->on($this->roles());
            $table->foreign('user_id')->references('id')->on($this->users());
        });

        Schema::create('user_abilities', function (Blueprint $table) {
            $table->integer('ability_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->unique(['ability_id', 'user_id']);

            $table->foreign('ability_id')->references('id')->on($this->abilities());
            $table->foreign('user_id')->references('id')->on($this->users());
        });

        Schema::create('role_abilities', function (Blueprint $table) {
            $table->integer('ability_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->unique(['ability_id', 'role_id']);

            $table->foreign('ability_id')->references('id')->on($this->abilities());
            $table->foreign('role_id')->references('id')->on($this->roles());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_abilities');
        Schema::drop('user_abilities');
        Schema::drop('user_roles');
        Schema::drop($this->roles());
        Schema::drop($this->abilities());
    }

    /**
     * Get the table name for the ability model.
     *
     * @return string
     */
    protected function abilities()
    {
        return Models::ability()->getTable();
    }

    /**
     * Get the table name for the role model.
     *
     * @return string
     */
    protected function roles()
    {
        return Models::role()->getTable();
    }

    /**
     * Get the table name for the user model.
     *
     * @return string
     */
    protected function users()
    {
        return Models::user()->getTable();
    }
}

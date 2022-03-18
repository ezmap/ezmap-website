<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('themes', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('snazzy_id')->nullable();
      $table->string('name');
      $table->text('description')->nullable();
      $table->string('url');
      $table->string('imageUrl')->nullable();
      $table->text('json');
      $table->text('author')->nullable();
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
    Schema::drop('themes');
  }
};

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
    Schema::create('maps', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned()->index();
      $table->string('title')->default('Untitled');
      $table->string('apiKey')->default('');
      $table->string('mapContainer');
      $table->integer('width');
      $table->integer('height');
      $table->boolean('responsiveMap');
      $table->float('latitude', 20, 15);
      $table->float('longitude', 20, 15);
      $table->text('markers');
      $table->text('mapOptions');
      $table->integer('theme_id')->default(0);
      $table->timestamps();

      $table->foreign('user_id')
          ->references('id')->on('users')
          ->onDelete('cascade');


    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('maps');
  }
};

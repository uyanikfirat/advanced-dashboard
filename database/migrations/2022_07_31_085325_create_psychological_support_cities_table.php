<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsychologicalSupportCitiesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('psychological_support_cities', function (Blueprint $table) {
      $table->increments('id');
      $table->foreignId('user_id');
      $table->string('title');
      $table->longText('content');
      $table->string('slug')->nullable();
      $table->string('city')->nullable();
      $table->string('seo_title')->nullable();
      $table->string('seo_description')->nullable();
      $table->boolean('status');
      $table->string('thumbnail')->nullable();
      $table->timestamps();
      $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
    Schema::dropIfExists('psychological_support_cities');
  }
}

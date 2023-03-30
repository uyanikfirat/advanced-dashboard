<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialistsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('specialists', function (Blueprint $table) {
      $table->increments('id');
      $table->foreignId('user_id');
      $table->string('title');
      $table->string('specialty');
      $table->longText('content');
      $table->string('slug')->nullable();
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
    Schema::dropIfExists('specialists');
  }
}

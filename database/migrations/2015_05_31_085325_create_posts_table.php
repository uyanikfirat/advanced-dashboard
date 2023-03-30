<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('posts', function (Blueprint $table) {
      $table->smallIncrements('id');
      $table->foreignId('user_id');
      $table->unsignedSmallInteger('category_id')->nullable();
      $table->string('title');
      $table->longText('content');
      $table->string('slug')->nullable();
      $table->string('seo_title')->nullable();
      $table->string('seo_description')->nullable();
      $table->string('focus_keyword')->nullable();
      $table->enum('type', ['post', 'page'])->default('post');
      $table->boolean('status')->default(1);
      $table->string('thumbnail')->nullable();
      $table->timestamps();
      $table->foreign('user_id')
      ->references('id')
      ->on('users')
      ->onDelete('cascade');
      $table->foreign('category_id')
      ->references('id')
      ->on('categories')
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
    Schema::dropIfExists('posts');
  }
}

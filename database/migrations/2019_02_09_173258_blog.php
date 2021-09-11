<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Blog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('blog_identifier',15)->unique();
	        $table->string('user_id',15)->index();
            $table->string('title');
            $table->string('text_path');
            $table->string('category_id',15)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}

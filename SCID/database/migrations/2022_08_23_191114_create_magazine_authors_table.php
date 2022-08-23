<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magazine_author', function (Blueprint $table) {
            $table->id();
            $table->integer('magazine_id');                                  
            $table->integer('author_id');                                  
            $table->foreign('magazine_id')->references('id')->on('magazines')
                ->onDelete('cascade');                                   
            $table->foreign('author_id')->references('id')->on('authors')
                ->onDelete('cascade');                                    
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
        Schema::dropIfExists('magazine_author');
    }
};

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeekerExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seeker_experiences', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->integer('seeker_id')->unsigned();
            $table->string('company');
            $table->string('job_title');
            $table->date('started');
            $table->date('ended')->nullable();
            $table->integer('present')->default(0);
            $table->text('description');
            $table->integer('days_experience');
            
            $table->timestamps();
            
            $table->foreign('seeker_id')->references('user_id')->on('seekers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seeker_experiences');
    }
}

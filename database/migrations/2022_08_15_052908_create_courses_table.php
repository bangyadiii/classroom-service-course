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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->boolean("certificate")->nullable();
            $table->string("thumbnail")->nullable();
            $table->integer("price")->default(0);
            $table->enum("type", ['free', 'premium']);
            $table->enum("status", ["draft", "published"]);
            $table->enum("level", ['beginner', "intermedite", "advanced"]);
            $table->foreignId("mentor_id")->constrained("mentors")->onDelete("cascade");
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
        Schema::dropIfExists('courses');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_managements', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->string('title', 200);
            $table->string('subtitle', 200);
            $table->string('image', 255)->nullable()->default(null);
            $table->longText('description')->nullable()->default(null);
            $table->string('section')->nullable()->default(null);
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
        Schema::dropIfExists('content_managements');
    }
}

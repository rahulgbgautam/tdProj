<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_rolls', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('menu_key')->nullable();
            $table->enum('read', ['0', '1'])->default('1');
            $table->enum('write', ['0', '1'])->default('1');
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
        Schema::dropIfExists('assign_rolls');
    }
}

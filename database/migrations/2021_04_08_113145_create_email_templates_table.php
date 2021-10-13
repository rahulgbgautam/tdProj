<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailTemplatesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->string('variable_name')->nullable();
            $table->text('title')->nullable();
            $table->longText('description')->nullable();
            $table->text('variable')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('email_templates');
    }
}

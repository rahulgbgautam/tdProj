<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DsProbsSubCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ds_probs_sub_category', function (Blueprint $table) {
            
            $table->bigIncrements('id');
            $table->integer('category_id');
            $table->string('sub_category_name', 255);
            $table->string('sub_category_display_name', 255);
            $table->text('pass_message');
            $table->text('fail_message');
            $table->text('remediation_message');
            $table->longText('pass_code', 100);
            $table->longText('fail_code', 100);
            $table->integer('max_score');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
            $table->enum('is_deleted', ['0', '1'])->default('0');
        });

        // Schema::table('ds_probs_sub_category', function($table)
        // {           
        //     $table->foreign('category_id')->references('id')->on('ds_probs_category')->onDelete('cascade');
        // });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ds_probs_sub_category');
    }
}

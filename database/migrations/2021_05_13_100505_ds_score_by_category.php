<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DsScoreByCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ds_score_by_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('domain_id');
            $table->integer('probs_category_id');
            $table->integer('average_score');
            $table->timestamps();
        });
        // Schema::table('ds_score_by_category', function($table) {
        //     $table->foreign('ds_domains')->references('id')->on('ds_domains')->onDelete('cascade');
        //     $table->foreign('probs_category_id')->references('id')->on('ds_probs_category')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ds_score_by_category');
    }
}

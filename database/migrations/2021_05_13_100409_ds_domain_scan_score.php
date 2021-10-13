<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DsDomainScanScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ds_domain_scan_score', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('domain_id');
            $table->integer('probs_category_id');
            $table->integer('probs_sub_category_id');
            $table->integer('score');
            $table->enum('status', ['Pass', 'Fail'])->default('Pass');
            $table->timestamps();
        });
        // Schema::table('ds_domain_scan_score', function($table) {
        //     $table->foreign('ds_domains')->references('id')->on('ds_domains')->onDelete('cascade');
        //     $table->foreign('probs_category_id')->references('id')->on('ds_probs_category')->onDelete('cascade');
        //     $table->foreign('probs_sub_category_id')->references('id')->on('probs_sub_category')->onDelete('cascade');
        // });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ds_domain_scan_score');
    }
}

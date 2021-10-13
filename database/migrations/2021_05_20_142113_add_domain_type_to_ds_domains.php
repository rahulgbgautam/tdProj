<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDomainTypeToDsDomains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ds_domains', function (Blueprint $table) {
            \DB::statement("ALTER TABLE `ds_domains` ADD `type` INT(11) NOT NULL DEFAULT '0' AFTER `status`, ADD `industry` INT(11) NOT NULL DEFAULT '0' AFTER `type`;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ds_domains', function (Blueprint $table) {
            //
        });
    }
}

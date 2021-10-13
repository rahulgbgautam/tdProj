<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDomainToDsDomains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ds_domains', function (Blueprint $table) {
             \DB::statement("ALTER TABLE `ds_domains` CHANGE `user_id` `user_id` INT(11) NULL;");
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

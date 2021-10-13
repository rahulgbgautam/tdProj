<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ds_domain_users', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->integer('user_id')->nullable();
            $table->integer('domain_id')->nullable();
            $table->integer('type');
            $table->integer('industry');
            $table->date('scan_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('subscription_id')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('added_as', ['S', 'C'])->default('C');
            $table->enum('is_deleted', ['0', '1'])->default('0');
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
        Schema::dropIfExists('domain_users');
    }
}

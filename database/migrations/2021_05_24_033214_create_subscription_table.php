<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('subscription_type', [ 'Yearly','Monthly', 'Membership'])->default('Membership');
            $table->integer('quantity');
            $table->decimal('price');
            $table->integer('total_amount');
            $table->date('expire_date');
            $table->string('transaction_number');
            $table->string('transaction_id');
            $table->integer('card_detail_id');
            $table->enum('transaction_status', [ 'Pending','Active', 'Inactive'])->default('Active');
            $table->text('capture_return');
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
        Schema::dropIfExists('subscriptions');
    }
}

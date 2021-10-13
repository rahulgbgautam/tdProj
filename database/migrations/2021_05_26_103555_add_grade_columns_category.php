text<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGradeColumnsCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ds_probs_category', function (Blueprint $table) {
            $table->text('grade_a')->after('category_name')->nullable();
            $table->text('grade_b')->after('grade_a')->nullable();
            $table->text('grade_c')->after('grade_b')->nullable();
            $table->text('grade_d')->after('grade_c')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ds_probs_category');
    }
}

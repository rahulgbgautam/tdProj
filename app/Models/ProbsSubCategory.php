<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProbsSubCategory extends Model
{
    use HasFactory;
    protected $table='ds_probs_sub_category';

    function getCategory(){

    	return $this->hasMany('App\Models\ProbsCategory');
    }

}

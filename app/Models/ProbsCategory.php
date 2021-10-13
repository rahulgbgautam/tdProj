<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProbsCategory extends Model
{
    use HasFactory;
    protected $table='ds_probs_category';

    function getSubCategory(){

    	return $this->hasMany('App\Models\ProbsSubCategory');
    }

}

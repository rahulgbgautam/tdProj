<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainScoreCategory extends Model
{
    protected $table = 'ds_score_by_category';
    protected $fillable = [
    	'domain_id',
    	'probs_category_id',
    	'average_score'
    ];
}

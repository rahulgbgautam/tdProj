<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverallRatingMessages extends Model
{
    use HasFactory;
    protected $table = 'ds_overall_rating_messages';
    protected $fillable = [
    	'grade',
    	'performance',
    	'message'
    ];
}

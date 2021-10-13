<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainScanScore extends Model
{
    use HasFactory;
    protected $table = 'ds_domain_scan_score';
    protected $fillable = [
    	'domain_id',
    	'probs_category_id',
    	'probs_sub_category_id',
    	'score',
    	'status'
    ];
}

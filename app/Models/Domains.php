<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domains extends Model
{
    use HasFactory;
    protected $table='ds_domains';
    protected $fillable = [
    	'last_scan_date',
    	'domain_name',
    	'average_score',
    ];

}

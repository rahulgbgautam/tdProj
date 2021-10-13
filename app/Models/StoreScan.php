<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreScan extends Model
{
    use HasFactory;
    protected $table = 'store_scan';
    protected $fillable = [
    	'user_id',
    	'domain_name',
    	'scan_date'
    ];
}

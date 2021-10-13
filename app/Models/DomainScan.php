<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainScan extends Model
{
    use HasFactory;
    protected $table = 'ds_domain_scan';
    // protected $fillable = [
    //     'id', 'user_id', 'domain_id', 'scan_date', 'expiry_date', 'subscription_id', 'average_score ', 'created_at', 'updated_at'
    // ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainsUser extends Model
{
    use HasFactory;
    protected $table = 'ds_domain_users';
    protected $fillable = [
    	'user_id',
    	'domain_id',
    	'type',
    	'industry',
    	'scan_date',
        'auto_payment',
    	'expiry_date',
    	'subscription_id',
    	'status',
    	'added_as',
    	'is_deleted'
    ];
}

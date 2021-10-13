<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainForPurchase extends Model
{
    use HasFactory;
    protected $table = 'domain_for_purchase';
    protected $fillable = [
    	'user_id',
    	'domain_name',
    	'type',
    	'industry'
    ];
}

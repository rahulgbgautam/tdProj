<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentManagement extends Model
{
    
    protected $table='content_managements';
    protected $fillable=['title','subtitle','description','section'];
}


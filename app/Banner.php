<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable=['title','subtitle','discription','banner_image','banner_type','status'];
}

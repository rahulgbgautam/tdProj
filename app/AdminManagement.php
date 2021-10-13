<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminManagement extends Model
{
    protected $table = "admin_managements";
    protected $fillable = ['name','email','password'];
    

}

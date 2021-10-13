<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    use HasFactory;
    protected $table = 'news_letter';
    protected $fillable = [
    	'email',
    	'status'
    ];
    ### function to insert or update news letter
   public function inserOrUpdateNewsletter($email){
    $newsletterInfo = $this->where('email', $email)->first();
    if($newsletterInfo){
    $data = array(
           'updated_at' => date('Y-m-d H:i:s')
       );
       $this->where('email', $email)->update($data);
    }
    else{
    $data = array(
           'email' => $email,
           'created_at' => date('Y-m-d H:i:s')
       );
       $this->insert($data);
   }
       return true;
   }
}



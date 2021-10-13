<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{	

    protected $fillable = ['label','variable_name','title','description','variable'];

    ### function to get email template on basis of the template name
	public static function getEmailByTemplate($template_name) {
		return self::where('variable_name', $template_name)
			->get()
			->first();

	}
	
    
}

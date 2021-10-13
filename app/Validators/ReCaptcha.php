<?php 
namespace App\Validators;
use GuzzleHttp\Client;
/**
 * 
 */
class ReCaptcha
{
	
	public function validate($attrbute, $value, $parameters, $validator)
	{
		$client = new Client;
		// $response = $client->post(
		// 	'https://www.google.com/recaptcha/api/siteverify',
		// 	[
		// 		'secret' => config('services.recaptcha.secret'),
		// 		'response' => $value
		// 	]
		// );

		$response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => array(
                'secret' => config('services.recaptcha.secret'),
				'response' => $value
            )
        ]);

		$body = json_decode((string)$response->getBody());
		return $body->success;
	}
}
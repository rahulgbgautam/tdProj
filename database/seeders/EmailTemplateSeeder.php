<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use DB;

class EmailTemplateSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		DB::table('email_templates')->insert(
			array(
				array(
					'label' => 'Admin Forgot Password',
					'variable_name' => 'adminForgotMail',
					'title' => 'Forgot Password',
					'description' => '<p>Dear {name},

							Thanks for the registration on our website.

							Please click on the below link to reset your password.

							{link}


							Thanks,
							{page_title}
							{site_link}</p',
					'variable' => '{name},{email},{link},{page_title},{site_link},{help_email}',
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
				),
				array(
					'label' => 'User Registration',
					'variable_name' => 'userRegistrationMail',
					'title' => 'Welcome to Trust Dom',
					'description' => 'Dear {name},
								Thank you for registering to Trust Dom. Before you start to use the service, we request you to verify your email address by clicking on the following link: {link}<br>
								In case you did not sign for the service, please ignore this auto generated email.<br>
								Thank you.
								',
					'variable' => '{name},{email},{link}',
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
				),
				array(
					'label' => 'welcome',
					'variable_name' => 'welcomemail',
					'title' => 'Welcome to Trust Dom',
					'description' => 'Dear {name},
								Welcome to Trust Dom<br>
								Thank you.
								',
					'variable' => '{name},{email}',
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
				),
				 array(
					'label' => 'End User Forgot Password',
					'variable_name' => 'userForgotPasswordMail',
					'title' => 'Trust Dom | Forgot Password',
					'description' => '<p><span style="font-size: 14px;">Dear</span> {name},

						Thanks for the registration on our website.

						Please click on the below link to reset your password.

						{resetpassword_link}


						Thanks,
						{page_title}
						{site_link}</p>
						',
					'variable' => '{name},{email},{page_title},{site_link},{help_email},{resetpassword_link}',
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
				), array(
					'label' => 'Transaction Completed',
					'variable_name' => 'transaction_success',
					'title' => 'Trust Dom | Transaction Completed',
					'description' => '<p><span style="font-size: 14px;">Dearr</span> {name},

						{message}

						Thanks,
						{page_title}
						{site_link}<p></p><p></p></p>
						',
					'variable' => '{name},{email},{page_title},{site_link},{help_email},{message}',
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
				), array(
					'label' => 'Transaction Failed',
					'variable_name' => 'transaction_failed',
					'title' => 'Trust Dom | Transaction Failed',
					'description' => '<p>Dear {name},

						{message}

						Thanks,
						{page_title}
						{site_link}<p></p><p></p><p></p></p>
						',
					'variable' => '{name},{email},{page_title},{site_link},{help_email},{message}',
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
				),
			)

		);
	}
}


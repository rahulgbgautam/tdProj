<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ContentManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('content_managements')->truncate();
		DB::table('content_managements')->insert(
			array(
				array(
					'title' => 'About Us',
					'subtitle' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
					'image' => '',
					'description' => 'Description of the About Us',
					'section' => 'About_us',
					'created_at' => date("Y-m-d H:i:s"),
            		'updated_at' => date("Y-m-d H:i:s"),
				),
				array(
					'title' => 'Terms and Conditions',
					'subtitle' => 'Terms and Conditions',
					'image' => '',
					'description' => 'Description of the Terms and Conditions',
					'section' => 'terms_and_condition',
					'created_at' => date("Y-m-d H:i:s"),
            		'updated_at' => date("Y-m-d H:i:s"),
				),
				array(
					'title' => 'Privacy Policy ',
					'subtitle' => 'Privacy Policy ',
					'image' => '',
					'description' => 'Description of the Privacy Policy ',
					'section' => 'terms_and_condition',
					'created_at' => date("Y-m-d H:i:s"),
            		'updated_at' => date("Y-m-d H:i:s"),
				),
				array(
					'title' => 'Cookies notification',
					'subtitle' => 'Cookies notification',
					'image' => '',
					'description' => 'Description of the Cookies notification',
					'section' => 'Cookies notification',
					'created_at' => date("Y-m-d H:i:s"),
            		'updated_at' => date("Y-m-d H:i:s"),
				),
				array(
					'title' => 'Whitepaper',
					'subtitle' => 'Whitepaper',
					'image' => '',
					'description' => 'Description of the Whitepaper',
					'section' => 'Whitepaper',
					'created_at' => date("Y-m-d H:i:s"),
            		'updated_at' => date("Y-m-d H:i:s"),
				),
				array(
					'title' => 'Research',
					'subtitle' => 'Research',
					'image' => '',
					'description' => 'Description of the Research',
					'section' => 'Research',
					'created_at' => date("Y-m-d H:i:s"),
            		'updated_at' => date("Y-m-d H:i:s"),
				),
				array(
					'title' => 'Whats inside the Dashboard?',
					'subtitle' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
					'image' => '',
					'description' => '',
					'section' => 'inside_dashboard',
					'created_at' => date("Y-m-d H:i:s"),
            		'updated_at' => date("Y-m-d H:i:s"),
				),
				array(
					'title' => 'Frequently asked questions (FAQ)',
					'subtitle' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry',
					'image' => '',
					'description' => '',
					'section' => 'faq',
					'created_at' => date("Y-m-d H:i:s"),
            		'updated_at' => date("Y-m-d H:i:s"),
				),
				array(
					'title' => 'Access Dashboard for an year to view full report',
					'subtitle' => 'FeaturesLorem Ipsum is simply dummy text of the printing setting industry.',
					'image' => '',
					'description' => 'Continous domain monitoring
										3rd party & domain portfolio Mgmt
										Deepweb search tool box
										Domain security remediation plans
										Dashboard Real-time analysis
										Affordable Subscription Plan',
					'section' => 'pricing_purchase_subscription',
					'created_at' => date("Y-m-d H:i:s"),
            		'updated_at' => date("Y-m-d H:i:s"),
				),
				array(
					'title' => 'Purchase Credits',
					'subtitle' => 'Purchase Monthly Credits',
					'image' => '',
					'description' => 'Scan 1 Domain on purchase of 1 credit 
										3rd party & domain portfolio Mgmt 
										Deepweb search tool box 
										Deepweb search tool box 
										Domain security remediation plans',
					'section' => 'pricing_purchase_monthly_credits',
					'created_at' => date("Y-m-d H:i:s"),
            		'updated_at' => date("Y-m-d H:i:s"),
				),
				array(
					'title' => 'Purchase Credits',
					'subtitle' => 'Purchase Yearly Credits',
					'image' => '',
					'description' => 'Scan 1 Domain on purchase of 1 credit
										3rd party & domain portfolio Mgmt 
										Deepweb search tool box 
										Deepweb search tool box 
										Domain security remediation plans',
					'section' => 'pricing_purchase_yearly_credits',
					'created_at' => date("Y-m-d H:i:s"),
            		'updated_at' => date("Y-m-d H:i:s"),
				),
				array(
					'title' => 'Access Dashboard for an year to view full report',
					'subtitle' => 'Includes 1 domain monitoring for 12 months',
					'image' => '',
					'description' => '',
					'section' => 'domain_monitoring',
					'created_at' => date("Y-m-d H:i:s"),
            		'updated_at' => date("Y-m-d H:i:s"),
				),
			)

		);
    }
}

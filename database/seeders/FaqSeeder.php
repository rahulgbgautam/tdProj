<?php

use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('faq')->truncate();
        DB::table('faq')->insert(
            array(
                array(
                    'question' => 'FAQ Question',                    
                    'answer' => 'FAQ Answer',                    
                    'status' => 'Active',                    
                )
            )

        );
    }
}


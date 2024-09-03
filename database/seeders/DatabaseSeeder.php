<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::table('languages')->insert(
            [
                'name' => 'English',
                'code' => 'en',
                'file_name' => 'en.json',
                'status' => '1',
            ],
        );


        DB::table('settings')->insert(
            [
                [
                    'id' => 1,
                    'type' => 'company_name',
                    'data' => 'eBroker'
                ],
                [
                    'id' => 2,
                    'type' => 'currency_symbol',
                    'data' => '$'
                ],
                [
                    'id' => 3,
                    'type' => 'ios_version',
                    'data' => '1.0.0'
                ],
                [
                    'id' => 4,
                    'type' => 'default_language',
                    'data' => 'en'
                ],
                [
                    'id' => 5,
                    'type' => 'force_update',
                    'data' => '0'
                ],
                [
                    'id' => 6,
                    'type' => 'android_version',
                    'data' => '1.0.0'
                ],
                [
                    'id' => 7,
                    'type' => 'number_with_suffix',
                    'data' => '0'
                ],
                [
                    'id' => 8,
                    'type' => 'maintenance_mode',
                    'data' => 0,
                ],
                [
                    'id' => 9,
                    'type' => 'privacy_policy',
                    'data' => '',
                ],
                [
                    'id' => 10,
                    'type' => 'terms_conditions',
                    'data' => '',
                ],
                [
                    'id' => 11,
                    'type' => 'company_tel1',
                    'data' => '',
                ],
                [
                    'id' => 12,
                    'type' => 'company_tel2',
                    'data' => '',
                ],
                [
                    'id' => 13,
                    'type' => 'razorpay_gateway',
                    'data' => '0',
                ],
                [
                    'id' => 14,
                    'type' => 'paystack_gateway',
                    'data' => '0',
                ],
                [
                    'id' => 15,
                    'type' => 'paypal_gateway',
                    'data' => '0',
                ],
                [
                    'id' => 16,
                    'type' => 'system_version',
                    'data' => '1.1.5',
                ],
                [
                    'id' => 17,
                    'type' => 'company_logo',
                    'data' => 'logo.png',
                ],
                [
                    'id' => 18,
                    'type' => 'web_logo',
                    'data' => 'web_logo.png',
                ],
                [
                    'id' => 19,
                    'type' => 'favicon_icon',
                    'data' => 'favicon.png.png',
                ],
                 [
                    'id' => 20,
                    'type' => 'web_footer_logo',
                    'data' => 'web_footer_logo.png',
                ],
                [
                    'id' => 21,
                    'type' => 'web_placeholder_logo',
                    'data' => 'placeholder.svg',
                ],
                [
                    'id' => 22,
                    'type' => 'splash_logo',
                    'data' => 'splash.svg',
                ],
                [
                    'id' => 23,
                    'type' => 'app_home_screen',
                    'data' => 'homeLogo.svg',
                ],
                
                
                



            ]
        );
    }
}


<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('settings')->delete();

        $setting = Setting::create([

            'title'=>'University System',
            'meta_title'=>'University System',
            'logo_path'=>'logo.png',
            'favicon_path'=>'favicon.png',
            'phone'=>'+880123456789',
            'email'=>'example@mail.com',
            'address'=>'Mirpur, Dhaka',
            'date_format'=>'d-m-Y',
            'time_format'=>'h:i A',
            'week_start'=>'1',
            'time_zone'=>'Asia/Dhaka',
            'currency'=>'USD',
            'currency_symbol'=>'$',
            'decimal_place'=>'2',
            'copyright_text'=>'2022 - University System | Created By_ <a href="https://hitechparks.com/" target="_blank">Hi-Tech Parks</a>',
            'status'=>true

        ]);
    }
}

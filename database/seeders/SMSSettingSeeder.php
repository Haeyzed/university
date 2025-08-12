<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\SMSSetting;

class SMSSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('sms_settings')->delete();

        $sms_settings = SMSSetting::query()->create([

            'vonage_key'=>'7e29c3ce',
            'vonage_secret'=>'6gK9ve4soFO6RP5d',
            'vonage_number'=>'ABC',
            'twilio_sid'=>'AC8f4bfd69c98ad28c8f3a1dc8a8cca836',
            'twilio_auth_token'=>'4c880b6a9061a145d5c2a92de7e51cdf',
            'twilio_number'=>'+14154461617',
            'status'=> true,

        ]);
    }
}

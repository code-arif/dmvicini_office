<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'title'         => 'Pinnacle Capital Group',
            'phone'         => '1234567890',
            'email'         => 'support@pinnaclealts.com',
            'name'          => 'David Vicini',
            'copyright'     => 'Copyright © 2025 Pinnacle Capital Group. All rights reserved.',
            'description'   => "Pinnacle Capital Group is a digital agency that creates and shares innovative digital product experiences tailored for startups and small businesses.
                                Through this platform, our team showcases project updates, creative work, and industry insights—giving users a behind-the-scenes look at
                                how we bring digital ideas to life.",
            'address'       => 'Cairo, Australia',
            'keywords'      => 'Pinnacle Capital Group',
            'author'        => 'David Vicini',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}

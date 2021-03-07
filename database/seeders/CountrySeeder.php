<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->delete();
        DB::table('countries')->insert([
            ['id' => 1, 'name' => 'India', 'code' => 'IND'],
            ['id' => 2, 'name' => 'Australia', 'code' => 'AUS'],
            ['id' => 3, 'name' => 'South Africa', 'code' => 'SAF'],
            ['id' => 4, 'name' => 'United State', 'code' => 'USA'],
        ]);
    }
}

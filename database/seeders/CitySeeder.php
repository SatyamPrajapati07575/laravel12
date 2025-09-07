<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        City::insert([
            ['country_id'=>1,'name'=>'Delhi'],
            ['country_id'=>1,'name'=>'Mumbai'],
            ['country_id'=>1,'name'=>'Bangalore'],
            ['country_id'=>2,'name'=>'New York'],
            ['country_id'=>2,'name'=>'Los Angeles'],
            ['country_id'=>3,'name'=>'Toronto'],
            ['country_id'=>3,'name'=>'Vancouver'],
        ]);
    }
}

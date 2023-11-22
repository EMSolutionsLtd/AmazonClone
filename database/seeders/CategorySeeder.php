<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert(['name' => 'Electronics']);
        DB::table('categories')->insert(['name' => 'Home']);
        DB::table('categories')->insert(['name' => 'Men\'s Fashion']);
        DB::table('categories')->insert(['name' => 'Women\'s Fashion']);
        DB::table('categories')->insert(['name' => 'Pets']);
        DB::table('categories')->insert(['name' => 'Arts & Crafts']);
    }
}

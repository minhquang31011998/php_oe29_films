<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Option;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Option::truncate();
        DB::table('options')->insert([
            ['name' => 'genre'],
            ['name' => 'quality'],
            ['name' => 'language'],
            ['name' => 'is_active'],
            ['name' => 'status',],
            ['name' => 'country'],
            ['name' => 'channel_type'],
        ]);
    }
}

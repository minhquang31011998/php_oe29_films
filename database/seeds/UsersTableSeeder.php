<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@gmail.vn',
            'role' => '1',
            'phone' => '0368695921',
            'address' => 'HN',
            'password' => Hash::make('12345678'),
        ]);
    }
}

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
        DB::table('users')->insert([
            'name' => 'Aymen',
            'email' => 'aymenweb5@gmail.com',
            'password' => bcrypt('123123'),
        ]);
    }
}

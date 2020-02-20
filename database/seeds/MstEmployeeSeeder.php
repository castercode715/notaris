<?php

use Illuminate\Database\Seeder;
use App\MstEmployee;

class MstEmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'tommy',
            'password' => Hash::make('tommy'),
            'remember_token' => str_random(10)
        ]);
    }
}

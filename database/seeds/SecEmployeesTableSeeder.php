<?php

use Illuminate\Database\Seeder;
use App\Models\SecEmployee;

class SecEmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SecEmployee::create([
            'username' => 'tommy',
            'password' => Hash::make('tommy'),
            'remember_token' => str_random(10)
        ]);
    }
}

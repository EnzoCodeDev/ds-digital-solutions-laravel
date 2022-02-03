<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'uuid'=>'123e4567-e89b-12d3-a456-426655440000',
            'name' => 'Usuario Prueba',
            'email' => 'usuarioPrueba@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole('admin');
        User::factory(99)->create();
    }
}

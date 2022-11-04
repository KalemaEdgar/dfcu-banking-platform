<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'admin@dfcuapp.com',
            'phone' => '0775623646',
            'cif' => '1234567890'
        ]);

        User::factory()->count(2)->create();

        User::factory()->blocked()->count(2)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Account::factory([
            'cif' => '1234567890',
            'account_id' => '1010113487'
        ])->create();

        Account::factory([
            'cif' => '1234567890',
            'account_id' => '2210333487'
        ])->create();

        Account::factory()->count(2)->create();

        Account::factory()->blocked()->count(2)->create();
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\accounts>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $users = User::pluck('id')->toArray();
        $cifs = User::pluck('cif')->toArray();
        return [
            'cif' => fake()->randomElement($cifs),
            'account_id' => fake()->randomNumber(9, true) . fake()->randomNumber(1, true),
            'balance' => fake()->randomNumber(7, true),
            'created_by' => fake()->randomElement($users),
        ];
    }

    public function blocked()
    {
        return $this->state(function (array $attributes) {
            $users = User::pluck('id')->toArray();
            return [
                'blocked' => true,
                'blocked_by' => $this->faker->randomElement($users),
                'blocked_at' => now(),
            ];
        });
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'cif' => fake()->randomNumber(6, true),
            'address' => fake()->address(),
            'date_of_birth' => fake()->date(),
            'gender' => fake()->randomElement(['male','female']),
            'nationality' => fake()->country(),
            'id_type' => fake()->randomElement(['National Id','Refugee Card','Drivers License','Passport']),
            'id_number' => strtoupper(fake()->randomElement(['CM','CF','PP','RF']) . fake()->randomNumber(9, true)),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
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

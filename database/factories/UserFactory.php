<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'id_role' => 1, 
            'id_usaha' => 1,
            'no_telepon' => $this->faker->phoneNumber(), 
            // 'email_verified_at' => now(),
            // 'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    /**
     * Set a custom id_usaha for the user.
     *
     * @param  int  $idUsaha
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withUsaha(int $idUsaha): static
    {
        return $this->state([
            'id_usaha' => $idUsaha,
        ]);
    }

    /**
     * Set a custom id_role for the user.
     *
     * @param  int  $idRole
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withRole(int $idRole): static
    {
        return $this->state([
            'id_role' => $idRole,
        ]);
    }
}
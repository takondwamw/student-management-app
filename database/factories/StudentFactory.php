<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'   => $this->faker->firstName(),
            'middle_name'  => $this->faker->lastName(),
            'last_name'    => $this->faker->lastName(),
            'student_id'   => $this->faker->unique()->randomNumber(),
            'address_1'    => $this->faker->streetAddress(),
            'address_2'    => $this->faker->country(),
            'standard_id'  => $this->faker->unique()->randomNumber(),
        ];
    }
}

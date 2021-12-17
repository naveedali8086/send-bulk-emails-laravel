<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'recipient' => $this->faker->email(),
            'subject' => $this->faker->sentence(),
            'body' => $this->faker->text(100),
        ];
    }
}

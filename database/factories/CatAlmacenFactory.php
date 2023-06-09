<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CatAlmacenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'nombre'    => $this->faker->text(10),
        ];
    }
}

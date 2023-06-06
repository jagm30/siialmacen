<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
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
            'nombre'    => $this->faker->sentence(2),
            'descripcion'   => $this->faker->text(),
            'categoria'   => $this->faker->text(15),
            'claveproducto'   => $this->faker->text(15),
            'precio'    => $this->faker->numberBetween(5,1000),
            'precioPromocion'   => $this->faker->numberBetween(5,1000),

        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use App\Models\Productr;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'name' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'price' => rand(1000, 20000),
            'user_id' => function() {
                return User::all()->random();
            },
        ];


    }
    }

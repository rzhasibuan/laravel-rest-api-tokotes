<?php

namespace Database\Factories;

use App\Models\CategoryNews;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryNewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = CategoryNews::class;

    public function definition()
    {
        return [
            'name' => $name = $this->faker->sentence,
            'slug' => strtolower(Str::slug($name . '-' . time())),

        ];
    }
}

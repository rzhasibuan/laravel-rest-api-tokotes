<?php

namespace Database\Factories;

use App\Models\CategoryNews;
use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = News::class;

    public function definition()
    {
        return [
            'category_news_id' => CategoryNews::factory(),
            'title' => $title = $this->faker->sentence,
            'slug' => strtolower(Str::slug($title . '-' . time())),
            'thumbnail' => $this->faker->word(),
            'content' => $this->faker->paragraph(30),
        ];
    }
}

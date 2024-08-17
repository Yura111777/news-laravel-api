<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>fake()->unique()->sentence(3),
            'description'=>fake()->text,
            'imgNewsPath'=>fake()->imageUrl,
            'created_at'=>fake()->dateTimeBetween('-2 years'),
            'updated_at'=>function(array $attributes){
                return fake()->dateTimeBetween($attributes['created_at']);
            }
        ];
    }
}

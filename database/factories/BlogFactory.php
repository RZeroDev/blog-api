<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    protected $model = Blog::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'slug' => Str::slug($this->faker->unique()->sentence),
            'content' => $this->faker->paragraph,
            'image' => $this->faker->randomElement([
                'https://picsum.photos/seed/picsum/200/300',
                'https://loremflickr.com/320/240',
                'https://placekitten.com/200/300',
                'https://unsplash.it/600/400?image=1043',
                'https://picsum.photos/600/400',
                'https://loremflickr.com/600/400/landscape',
                'https://picsum.photos/1200/600',
                'https://unsplash.it/1200/600?image=1081',
                'https://loremflickr.com/1200/600/nature',
            ]),
        ];
    }
}

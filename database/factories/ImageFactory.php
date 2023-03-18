<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dir = 'images';
        $filepath = public_path('/storage//'.$dir);

        if (! File::exists($filepath)) {
            File::makeDirectory($filepath);
        }

        $file = $this->faker->image($filepath, 400, 400, null, false);

        return [
            'name' => $file,
            'file' => $dir.'/'.$file,
            'enable' => $this->faker->boolean(),
        ];
    }
}

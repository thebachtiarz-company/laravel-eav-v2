<?php

namespace TheBachtiarz\EAV\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TheBachtiarz\EAV\Models\Eav;

/**
 * @extends Factory<Eav>
 */
class EavFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Model>
     */
    protected $model = Eav::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}

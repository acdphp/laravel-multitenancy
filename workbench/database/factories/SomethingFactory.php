<?php

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Something;

/**
 * @template TModel of \Workbench\App\Models\Something
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class SomethingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Something::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'foo' => fake()->word,
            'company_id' => CompanyFactory::new()->create()->id,
        ];
    }
}

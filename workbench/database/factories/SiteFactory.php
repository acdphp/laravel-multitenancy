<?php

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Site;

/**
 * @template TModel of \Workbench\App\Models\Site
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<TModel>
 */
class SiteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Site::class;

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

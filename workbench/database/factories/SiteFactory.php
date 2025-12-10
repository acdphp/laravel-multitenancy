<?php

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Site;

/**
 * @template TModel of Site
 *
 * @extends Factory<TModel>
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
     * {@inheritDoc}
     */
    public function definition()
    {
        return [
            'foo' => $this->faker->word,
            'company_id' => CompanyFactory::new()->create()->id,
        ];
    }
}

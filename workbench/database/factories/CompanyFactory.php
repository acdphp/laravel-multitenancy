<?php

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Company;

/**
 * @template TModel of Company
 *
 * @extends Factory<TModel>
 */
class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Company::class;

    /**
     * {@inheritDoc}
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
        ];
    }
}

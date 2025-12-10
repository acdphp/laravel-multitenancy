<?php

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Product;

/**
 * @template TModel of Product
 *
 * @extends Factory<TModel>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<TModel>
     */
    protected $model = Product::class;

    /**
     * {@inheritDoc}
     */
    public function definition()
    {
        return [
            'site_id' => SiteFactory::new()->create()->id,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Http\Models\v1\Type_frequency_nodes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TypeFrequencyNodesFactory extends Factory
{
    protected $model = Type_frequency_nodes::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'name_frequency' => $this->faker->name(),
            'band_id' => $this->faker->word(),
            'base_frequency' => $this->faker->randomNumber(),
            'loraWan_version' => $this->faker->word(),
            'regional_params_version' => $this->faker->word(),
            'loraWan_class' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}

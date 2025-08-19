<?php

namespace Database\Factories;

use App\Http\Models\v1\ConsumptionSummary;
use App\Http\Models\v1\Water_meter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class Consumption_summaryFactory extends Factory
{
    protected $model = ConsumptionSummary::class;

    public function definition(): array
    {
        return [
            'total_consumption' => $this->faker->randomFloat(),
            'start_index' => $this->faker->randomFloat(),
            'end_index' => $this->faker->randomFloat(),
            'date_calculation' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'water_meter_id' => Water_meter::factory(),
        ];
    }
}

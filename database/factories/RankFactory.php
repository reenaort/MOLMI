<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Rank;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rank>
 */
class RankFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rank::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dep_id' => Department::where('status', 1)->get()->random()->id,
            'rank_name' => Str::title($this->faker->name),
            'rank_tags' => $this->faker->randomElement(['asndakds','asdadnad','adsiadn','asdnadnad','asdjadsjad','asdaidsjad','asdnads','asdkadasd','asdinadsasd','asdadsjasdjads','isfgisfdjs','sdfishdf','sdjoaosjd','adsfjd']),
        ];
    }
}

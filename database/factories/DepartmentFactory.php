<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Department::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dep_name' => Str::title($this->faker->name),
            'dep_tags' => $this->faker->randomElement(['asndakds','asdadnad','adsiadn','asdnadnad','asdjadsjad','asdaidsjad','asdnads','asdkadasd','asdinadsasd','asdadsjasdjads','isfgisfdjs','sdfishdf','sdjoaosjd','adsfjd']),
        ];
    }
}

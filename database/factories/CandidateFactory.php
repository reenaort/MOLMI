<?php

namespace Database\Factories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Department;
use App\Models\Rank;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Candidate::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $dep_id = Department::where('status', 1)->get()->random()->id;

        $rank_id = Rank::where('status', 1)->get()->random()->id;

        return [
            'candidate_name' => $this->faker->name(),
            'contact_no' => $this->faker->phoneNumber(),
            'email' => $this->faker->email,
            'dob' => $this->faker->date(),
            'dep_id' => $dep_id,
            'rank_id' => $rank_id,
            'coc_no' => $this->faker->text(10),
            'indos_no' => $this->faker->text(10),
            'passport_no' => $this->faker->text(10),
            'type' => rand(1, 2),
            'till_date' => $this->faker->date(),
        ];
    }
}

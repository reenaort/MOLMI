<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Department;
use App\Models\Rank;
use App\Models\Course;
use App\Models\TrainingCenter;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $cat_id = Category::where('status',1)->get()->random()->id;

        $subcat_id = SubCategory::where('status',1)->get()->random()->id;

        $dep_id = Department::where('status', 1)->get()->random()->id;

        $rank_id = Rank::where('status', 1)->get()->random()->id;

        $tc_id = TrainingCenter::all()->random()->id;

        return [
            'cat_id' => $cat_id,
            'subcat_id' => $subcat_id,
            'dep_id' => $dep_id,
            'rank_id' => $rank_id,
            'course_name' => $this->faker->name(),
            'course_code' => $this->faker->text(10),
            'course_by' => rand(1,2),
            'tc_id' => $tc_id,
            'duration' => rand(10,20),
            'course_type' => $this->faker->randomElement(['Simulator Based', 'Vessel Specific', 'Classroom Based', 'CBT']),
            'course_mode' => rand(1,2),
            'course_priority' => rand(1,2),
            'course_repeated' => rand(0,1),
            'course_intervals' => rand(1,6),
        ];
    }
}

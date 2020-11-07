<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_id = User::all()->random(1)->first()->id;
        $project_id = Project::all()->random(1)->first()->id;

        return [
            'user_id'     => $user_id,
            'project_id'  => $project_id,
            'content'     => $this->faker->text,
            'report_date' => $this->faker->date(),
        ];
    }
}

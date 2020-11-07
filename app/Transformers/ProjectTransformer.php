<?php

namespace App\Transformers;

use App\Models\Project;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class ProjectTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param \App\Models\Project $project
     * @return array
     */
    public function transform(Project $project)
    {
        return [
            'id'         => (int) $project->id,
            'name'       => (string) $project->name,
            'created_at' => Carbon::make($project->created_at)->format('Y-m-d h:i:s'),
            'updated_at' => Carbon::make($project->updated_at)->format('Y-m-d h:i:s'),
        ];
    }
}

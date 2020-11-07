<?php

namespace App\Transformers;

use App\Models\Report;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class ReportTransformer extends Transformer
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
     * @param \App\Models\Report $report
     * @return array
     */
    public function transform(Report $report)
    {
        return [
            'id'           => (int) $report->id,
            'user_id'      => (int) $report->user_id,
            'project_id'   => (int) $report->project_id,
            'content'      => $report->content,
            'user_name'    => optional($report->user)->name,
            'email'        => optional($report->user)->email,
            'project_name' => optional($report->project)->name,
            'report_date'  => Carbon::make($report->report_date)->format('Y-m-d'),
            'created_at'   => Carbon::make($report->created_at)->format('Y-m-d h:i:s'),
            'updated_at'   => Carbon::make($report->updated_at)->format('Y-m-d h:i:s'),
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\Report;
use App\Transformers\ReportTransformer;

class ReportsExport
{
    public function headings(): array
    {
        return [
            'id',
            'user_id',
            'project_id',
            'content',
            'user_name',
            'email',
            'project_name',
            'report_date',
            'created_at',
            'updated_at',
        ];
    }

    public function exportFile($path)
    {
        $listReport = Report::all();
        $list = [];
        foreach ($listReport as $report) {
            $data = (new ReportTransformer())->transform($report);
            array_push($list, $data);
        }

        $listUser = array_merge([$this->headings()], $list);

        $fp = fopen($path, 'w');
        foreach ($listUser as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
    }
}

<?php

namespace App\Exports;

use App\Models\Report;
use App\Transformers\ReportTransformer;
use App\Transformers\UserTransformer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection, WithHeadings
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

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $listReport = Report::all();
        $a = [];
        foreach ($listReport as $report) {
            $data = (new ReportTransformer())->transform($report);
            array_push($a, $data);
        }

        return collect($a);
    }
}

<?php

namespace App\Imports;

use App\Models\Report;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReportsImport implements ToCollection,WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $reports)
    {
        foreach ($reports as $report) {
            Report::create([
                'user_id'     => $report['user_id'],
                'project_id'  => $report['project_id'],
                'content'     => $report['content'],
                'report_date' => $report['report_date'],
            ]);
        }
    }
}

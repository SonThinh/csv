<?php

namespace App\Console\Commands;

use App\Imports\ReportsImport;
use App\Imports\UsersImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use function PHPUnit\Framework\isNull;

class ImportFileCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:file {object} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import file csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return \Maatwebsite\Excel\Excel
     */
    public function handle()
    {
        switch ($this->argument('object')) {
            case 'user':
                $object = new UsersImport();
                break;
            case 'report':
                $object = new ReportsImport();
                break;
            default:
                $this->error('Model not found');
                $object = null;
        }

        try {
            if ($object !== null) {
                Excel::import($object, $this->option('path'), null, \Maatwebsite\Excel\Excel::CSV);
                $this->info('Import file to database successfully.');
            }
        } catch (\Exception $exception) {
            if ($object === null) {
                $this->error('');
            }

            $this->error($exception->getMessage());
        }
    }
}

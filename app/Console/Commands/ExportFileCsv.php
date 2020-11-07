<?php

namespace App\Console\Commands;

use App\Exports\ReportsExport;
use App\Exports\UsersExport;
use App\Http\Controllers\API\UserController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Config;
use Maatwebsite\Excel\Facades\Excel;
use function PHPUnit\Framework\isNull;

class ExportFileCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:file {object} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export file csv';

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
     * @return int
     */
    public function handle()
    {
        $arr = explode('/', $this->option('path'));

        switch ($this->argument('object')) {
            case 'user':
                $object = new UsersExport();
                break;
            case 'report':
                $object = new ReportsExport();
                break;
            default:
                $this->error('Model not found');
                $object = null;
        }
        try {
            $file_name = array_pop($arr);
            if ($object !== null) {
                $object->collect($this->option('path'));
                $this->info('Export file to '.$this->option('path').' '.'successfully.');
            }
        } catch (\Exception $exception) {
            if ($object === null) {
                $this->error('');
            }

            $this->error($exception->getMessage());
        }
    }
}

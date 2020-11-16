<?php

namespace App\Console\Commands;

use App\Exports\ReportsExport;
use App\Exports\UsersExport;
use App\Http\Controllers\API\UserController;
use App\Models\User;
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
     * @return void
     */
    public function handle()
    {
        $arr = explode('.', $this->option('path'));
        $file_mime = array_pop($arr);
        $mimetype = ['csv', 'xlsx'];
        if (! in_array($file_mime, $mimetype)) {
            $this->error('The file must be a file of type:'.implode(',', $mimetype).'.');

            return;
        }
        $object = $this->chooseModel($this->argument('object'));
        try {
            $this->handlingModel($object, $this->option('path'));
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * @param $option
     * @return \App\Exports\ReportsExport|\App\Exports\UsersExport
     */
    public function chooseModel($option)
    {
        switch ($option) {
            case 'user':
                $object = new UsersExport();
                break;
            case 'report':
                $object = new ReportsExport();
                break;
            default:
                $object = null;
        }

        return $object;
    }

    /**
     * @param $object
     * @param $path
     */
    public function handlingModel($object, $path)
    {
        if ($object !== null) {
            $object->exportFile($path);
            $this->info('Export file to '.$path.' '.'successfully.');
        } else {
            $this->error('Model not found');
        }
    }
}

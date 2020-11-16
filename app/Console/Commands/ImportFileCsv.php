<?php

namespace App\Console\Commands;

use App\Imports\ReportsImport;
use App\Imports\UsersImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

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
     * @return \App\Imports\ReportsImport|\App\Imports\UsersImport
     */
    public function chooseModel($option)
    {
        switch ($option) {
            case 'user':
                $object = new UsersImport();
                break;
            case 'report':
                $object = new ReportsImport();
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
        if ($object != null) {
            $results = $object->importFile($path);
            if ($results == null) {
                $this->info('Import file to database successfully.');
            }
            $this->handlingErrors($object->object(), $results);
        } else {
            $this->error('Model not found');
        }
    }

    public function handlingErrors($object, $array)
    {
        foreach ($array as $ob) {
            if ($object == 'user') {
                if (count($ob['name']) != 0) {
                    $this->error('Error in row '.$ob['row'].' '.$ob['name'][0]);
                }
                if (count($ob['email']) != 0) {
                    $this->error('Error in row '.$ob['row'].' '.$ob['email'][0]);
                }
            }
        }
    }
}

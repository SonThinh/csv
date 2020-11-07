<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileImportRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\DeclareDeclare;

class UserController extends Controller
{
    function change_key($array, $old_key, $new_key)
    {

        if (! array_key_exists($old_key, $array)) {
            return $array;
        }

        $keys = array_keys($array);
        $keys[array_search($old_key, $keys)] = $new_key;

        return array_combine($keys, $array);
    }

    public function userImport(FileImportRequest $request)
    {
        if (($handle = fopen($request->file('file'), 'r')) !== false) {
            # Set the parent multidimensional array key to 0.
            $nn = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                # Count the total keys in the row.
                $c = count($data);
                # Populate the multidimensional array.
                for ($x = 1; $x < $c; $x++) {
                    $csvarray[$nn][$x] = $data[$x];
                }
                $nn++;
            }

            $array = array_splice($csvarray, 1);

            $list = [];
            foreach ($array as $user) {
                $keys = array_keys($user);
                $new_key = [
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ];
                $keys[array_search($keys, $keys)] = $new_key;
                $a = array_combine($new_key, $user);
                array_push($list, $a);
            }

            foreach ($list as $user) {
                User::create([
                    'name'              => $user['name'],
                    'email'             => $user['email'],
                    'email_verified_at' => now(),
                    'created_at'        => $user['created_at'],
                    'updated_at'        => $user['updated_at'],
                    'password'          => Hash::make('password'),
                ]);
            }
            # Close the File.
            fclose($handle);
        }
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
        ];
    }

    public function userExport()
    {
        $users = User::all();
        $list = [];
        foreach ($users as $user) {
            $data = (new UserTransformer())->transform($user);
            array_push($list, $data);
        }
        $listUser = array_merge([$this->headings()], $list);
        $file_name = 'test.csv';
        $fp = fopen($file_name, 'w');
        foreach ($listUser as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
    }
}

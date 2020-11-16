<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileImportRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function changeKeySpliceId(array $array, $keyRow)
    {
        $keys = array_keys($array);
        $keys[array_search($keys, $keys)] = $this->headings();
        $combineKeyValue = array_combine($this->headings(), $array[$keyRow]);

        return $combineKeyValue;
    }

    public function checkValidate(array $array, $keyRow)
    {
        $errors = [];
        $data = [];
        $validator = Validator::make($array, [
            'name'  => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email',
        ]);

        if (count($validator->getMessageBag())) {
            array_push($errors, [
                'row'   => $keyRow,
                'name'  => isset($validator->getMessageBag()->toArray()['name']) ? $validator->getMessageBag()
                                                                                             ->toArray()['name'] : 0,
                'email' => isset($validator->getMessageBag()->toArray()['email']) ? $validator->getMessageBag()
                                                                                              ->toArray()['email'] : 0,
            ],);
        } else {
            array_push($data, $array);
        }

        return [
            'data'  => $data,
            'error' => $errors,
        ];
    }

    public function addDb(array $user)
    {
        User::create([
            'name'              => $user['name'],
            'email'             => $user['email'],
            'email_verified_at' => now(),
            'created_at'        => $user['created_at'],
            'updated_at'        => $user['updated_at'],
            'password'          => Hash::make('password'),
        ]);
    }

    public function userImport(FileImportRequest $request)
    {
        if (($handle = fopen($request->file('file'), 'r')) !== false) {
            $countRow = 1;
            $flag = true;
            $errors = [];
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if ($flag) {
                    $flag = false;
                    $countRow++;
                    continue;
                }
                $countCol = count($data);
                for ($i = 0; $i < $countCol; $i++) {
                    $dataCsv[$countRow][$i] = $data[$i];
                }
                $arrChangeKey = $this->changeKeySpliceId($dataCsv, $countRow);
                $splice = array_splice($arrChangeKey, 1);
                $user = $this->checkValidate($splice, $countRow);
                $countRow++;

                if (count($user['data']) != 0) {
                    $this->addDb($user['data'][0]);
                } else {
                    array_push($errors, $user['error'][0]);
                }
            }
            fclose($handle);

            return $errors;
        }

        return false;
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
        $file_name = 'D:\test.csv';
        $fp = fopen($file_name, 'w');
        foreach ($listUser as $fields) {
            //$string = mb_convert_encoding($fields, "UTF-8", "Shift-JIS, EUC-JP, JIS, SJIS, JIS-ms, eucJP-win, SJIS-win, ISO-2022-JP,ISO-2022-JP-MS, SJIS-mac, SJIS-Mobile#DOCOMO, SJIS-Mobile#KDDI,SJIS-Mobile#SOFTBANK, UTF-8-Mobile#DOCOMO, UTF-8-Mobile#KDDI-A,UTF-8-Mobile#KDDI-B, UTF-8-Mobile#SOFTBANK, ISO-2022-JP-MOBILE#KDDI");
            fputcsv($fp, $fields);
        }

        fclose($fp);
    }
}

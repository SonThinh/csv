<?php

namespace App\Exports;

use App\Models\User;
use App\Transformers\UserTransformer;

class UsersExport
{
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

    public function exportFile($path)
    {
        $users = User::all();
        $list = [];
        foreach ($users as $user) {
            $data = (new UserTransformer())->transform($user);
            array_push($list, $data);
        }
        $listUser = array_merge([$this->headings()], $list);

        $fp = fopen($path, 'w');
        //UTF-8 BOM byte order mark
        //fputs( $fp, "\xEF\xBB\xBF" );
        foreach ($listUser as $fields) {
            //$string = mb_convert_encoding($fields, "UTF-8", "Shift-JIS, EUC-JP, JIS, SJIS, JIS-ms, eucJP-win, SJIS-win, ISO-2022-JP,ISO-2022-JP-MS, SJIS-mac, SJIS-Mobile#DOCOMO, SJIS-Mobile#KDDI,SJIS-Mobile#SOFTBANK, UTF-8-Mobile#DOCOMO, UTF-8-Mobile#KDDI-A,UTF-8-Mobile#KDDI-B, UTF-8-Mobile#SOFTBANK, ISO-2022-JP-MOBILE#KDDI");
            fputcsv($fp, $fields);
        }

        fclose($fp);
    }
}

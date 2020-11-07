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

    public function collect($path)
    {
        $users = User::all();
        $list = [];
        foreach ($users as $user) {
            $data = (new UserTransformer())->transform($user);
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

<?php

namespace App\Imports;

use App\Http\Requests\FileImportRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersImport extends BaseImport
{
    public function object()
    {
        return 'user';
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
                                                                                             ->toArray()['name'] : [],
                'email' => isset($validator->getMessageBag()->toArray()['email']) ? $validator->getMessageBag()
                                                                                              ->toArray()['email'] : [],
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
}

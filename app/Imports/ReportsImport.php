<?php

namespace App\Imports;

use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ReportsImport extends BaseImport
{
    public function object()
    {
        return 'report';
    }

    public function headings(): array
    {
        return [
            'id',
            'user_id',
            'project_id',
            'content',
            'user_name',
            'email',
            'project_name',
            'report_date',
            'created_at',
            'updated_at',
        ];
    }

    public function checkValidate(array $array, $keyRow)
    {
        dd($array);
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

    public function addDb($record)
    {
        Report::create([
            'user_id'     => $record['user_id'],
            'project_id'  => $record['project_id'],
            'content'     => $record['content'],
            'report_date' => $record['report_date'],
            'created_at'  => $record['created_at'],
            'updated_at'  => $record['updated_at'],
        ]);
        User::updateOrCreate([
            'email' => $record['email'],
        ], [
            'name'              => $record['user_name'],
            'email'             => $record['email'],
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at'        => $record['created_at'],
            'updated_at'        => $record['updated_at'],
        ]);
        Project::updateOrCreate([
            'name' => $record['project_name'],
        ], [
            'name'       => $record['project_name'],
            'created_at' => $record['created_at'],
            'updated_at' => $record['updated_at'],
        ]);
    }
}

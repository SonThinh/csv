<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $users)
    {
        dd($users);
        foreach ($users as $user) {
            User::creat([
                'name'              => $user['name'],
                'email'             => $user['email'],
                'email_verified_at' => $user['email_verified_at'],
                'created_at'        => $user['created_at'],
                'updated_at'        => $user['updated_at'],
                'password'          => Hash::make('password'),
            ]);
        }
    }
}

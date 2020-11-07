<?php

namespace App\Transformers;

use App\Models\User;
use Carbon\Carbon;
use Flugg\Responder\Transformers\Transformer;

class UserTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param \App\Models\User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'                => (int) $user->id,
            'name'              => (string) $user->name,
            'email'             => (string) $user->email,
            'created_at'        => Carbon::make($user->created_at)->format('Y-m-d h:i:s'),
            'updated_at'        => Carbon::make($user->updated_at)->format('Y-m-d h:i:s'),
        ];
    }
}

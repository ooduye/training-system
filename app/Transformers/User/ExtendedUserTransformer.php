<?php

namespace App\Transformers\User;

use App\Models\User;
use LukeVear\LaravelTransformer\AbstractTransformer;

class ExtendedUserTransformer extends AbstractTransformer
{
    /**
     * Transform the supplied data.
     *
     * @param User $model
     * @return array
     */
    public function run($model)
    {
        return [
            'id'    => $model->id,
            'name'  => $model->name,
            'email'  => $model->email,
            'username'  => $model->username,
            'profile'  => $model->profile,
            'skill'  => $model->skill,
        ];
    }
}

<?php

namespace App\Transformers\User;

use App\Models\User;
use LukeVear\LaravelTransformer\AbstractTransformer;

class SimpleUserTransformer extends AbstractTransformer
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
            'profileName'  => $model->profile->name,
            'skillName'  => $model->skill->name,
        ];
    }
}

<?php

namespace App\Transformers\Skill;

use App\Models\Skill;
use LukeVear\LaravelTransformer\AbstractTransformer;

class SkillTransformer extends AbstractTransformer
{
    /**
     * Transform the supplied data.
     *
     * @param Skill $model
     * @return array
     */
    public function run($model)
    {
        return [
            'id'    => $model->id,
            'name'  => $model->name,
            'activities'  => $model->activities,
            'users'  => $model->users,
        ];
    }
}

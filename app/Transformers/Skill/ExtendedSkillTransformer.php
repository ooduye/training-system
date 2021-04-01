<?php

namespace App\Transformers\Skill;

use App\Models\Skill;
use App\Transformers\Activity\SimpleActivityTransformer;
use LukeVear\LaravelTransformer\AbstractTransformer;

class ExtendedSkillTransformer extends AbstractTransformer
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
            'activities'  => transforms($model->activities, new SimpleActivityTransformer()),
        ];
    }
}

<?php

namespace App\Transformers\Activity;

use App\Models\Activity;
use LukeVear\LaravelTransformer\AbstractTransformer;

class ActivityTransformer extends AbstractTransformer
{
    /**
     * Transform the supplied data.
     *
     * @param Activity $model
     * @return array
     */
    public function run($model)
    {
        return [
            'id'    => $model->id,
            'title'  => $model->title,
            'description'  => $model->description,
            'skillName'  => $model->skill->name,
            'start_date'  => $model->start_date,
            'end_date'  => $model->end_date,
        ];
    }
}

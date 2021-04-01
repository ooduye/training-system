<?php

namespace App\Transformers\Activity;

use Carbon\Carbon;
use App\Models\Activity;
use App\Transformers\User\SimpleUserTransformer;
use LukeVear\LaravelTransformer\AbstractTransformer;

class SimpleActivityTransformer extends AbstractTransformer
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
            'skill_id'  => $model->skill->id,
            'skill_name'  => $model->skill->name,
            'title'  => $model->title,
            'description'  => $model->description,
            'start_date'  => Carbon::parse($model->start_date)->format('F d, Y'),
            'end_date'  => Carbon::parse($model->end_date)->format('F d, Y'),
            'participants' => transforms($model->participants, new SimpleUserTransformer()),
        ];
    }
}

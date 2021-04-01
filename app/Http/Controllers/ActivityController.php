<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityParticipant;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function show($id)
    {
        $skill = Skill::find($id)->with(['activity' => function ($query) {
            $query->orderBy('start_date');
        }, 'activity.activityParticipant.user'])->get();

        if (!$skill) {
            return response()->json([
                'message' => 'Data cannot be processed'
            ], 422);
        }

        return response()->json([
            'data' => $skill->toArray()
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'skill_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'startdate' => 'required',
            'enddate' => 'required',
            'participants' => 'required',
        ]);

        $activity = Activity::create([
            'title' => $request->title,
            'description' => $request->description,
            'skill_id' => $request->skill_id,
            'start_date' => $request->startdate,
            'end_date' => $request->enddate,
        ]);

        $this->addParticipants($request->participants, $activity);

        if ($activity)
            return response()->json([
                'message' => 'create success',
            ], 200);
        else
            return response()->json([
                'message' => 'Data cannot be processed'
            ], 422);
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'message' => 'Cannot find Activity'
            ], 400);
        }

        $updated = $activity->fill($request->all())->save();

        $this->addParticipants($request->participants, $activity);

        if ($updated)
            return response()->json([
                'message' => 'update success'
            ], 200);
        else
            return response()->json([
                'message' => 'Data cannot be processed'
            ], 422);
    }

    public function destroy($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'message' => 'Cannot find Activity'
            ], 400);
        }

        if ($activity->delete() && $activity->activityParticipant()->delete()) {
            return response()->json([
                'message' => 'update success'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Data cannot be processed'
            ], 422);
        }
    }

    protected function addParticipants($participants, $activity) {
        foreach ($participants as $participant) {
            $user = User::find($participant);

            if ($user->skill_id == $activity->skill_id) {
                $activityParticipant = new ActivityParticipant();
                $activityParticipant->user_id = $user->id;
                $activityParticipant->activity_id = $activity->id;
                $activityParticipant->save();
            }
        }
    }
}

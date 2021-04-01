<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityParticipant;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    public function show($id)
    {
        $skill = Skill::where('id', $id)->with(['activities' => function ($query) {
            $query->orderBy('start_date');
        }, 'activities.participants'])->get();

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
        $validator = Validator::make($request->all(), [
            'skill_id' => 'required|exists:skills,id',
            'title' => 'required',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|after:start_date',
            'participants' => 'required|array',
            'participants.*' => 'required|distinct|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Data cannot be processed',
                'error' => $validator->errors(),
            ], 422);
        }

        $activity = Activity::create([
            'title' => $request->title,
            'description' => $request->description,
            'skill_id' => $request->skill_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
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

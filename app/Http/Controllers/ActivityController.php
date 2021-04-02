<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityParticipant;
use App\Models\Skill;
use App\Models\User;
use App\Transformers\Skill\ExtendedSkillTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group  Activities management
 *
 * APIs for managing activities
 */
class ActivityController extends Controller
{
    /**
     * Get all the activities for a Skill
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @urlParam  id required The ID of the Skill.
     *
     * @transformercollection  App\Transformers\Skill\ExtendedSkillTransformer
     * @transformerModel  App\Models\Skill
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $skill = Skill::find($id);

        if (!$skill) {
            return response()->json([
                'message' => 'Data cannot be processed'
            ], 422);
        }

        return response()->json([
            'data' => transforms($skill, new ExtendedSkillTransformer())
        ], 200);
    }

    /**
     * Create an activity
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam  skill_id int required The id of the skill. Example: 9
     * @bodyParam  title string required Title of the activity.
     * @bodyParam  description string required Description of the activity.
     * @bodyParam  start_date datetime required Start time of the activity.
     * @bodyParam  end_date datetime required End time of the activity.
     * @bodyParam  participants array required Array of IDs of users to participate in activity.
     *
     * @response  {
     *  "message": 'create success',
     * }
     *
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
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

    /**
     * Update an activity
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @urlParam  id required The ID of the Skill.
     *
     * @response  {
     *  "message": 'update success',
     * }
     *
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
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

    /**
     * Delete an activity
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @urlParam  id required The ID of the Skill.
     *
     * @response  {
     *  "message": 'update success',
     * }
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
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

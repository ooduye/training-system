<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

/**
 * @group  Skill management
 *
 * APIs for managing skills
 */
class SkillController extends Controller
{
    /**
     * Get all the Skills
     *
     * @transformerModel  App\Models\Skill
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $skills = Skill::all();

        return response()->json([
            'data' => $skills
        ], 200);
    }
}

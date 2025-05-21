<?php

namespace Modules\Leads\App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Modules\Leads\App\Models\LeadSources;
use Modules\Leads\App\Models\LeadStatus;

use Modules\User\App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeadStatusController extends Controller
{

    // Create Lead Status
    public function createStatus(Request $request): \Illuminate\Http\JsonResponse
    {

        $operator = Auth::user();

        if ($operator->can('users:create')) {

            $validate = Validator::make($request->all(), [
                'status_name' => ['required', 'string', 'unique:lead_statuses'],
                'active' => ['required'],
            ]);

            // Validator Has Error
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()->first()], 400);
            }


            $status = new LeadStatus([
                'status_name' => $request->status_name,
                'color' => $request->color,
                'active' => $request->active,

            ]);

            $status->save();


            return response()->json([
                'message' => 'status has created successful',
            ], 201);


            // If admin dos not permission to create user
        } else {
            return response()->json([
                'message' => 'You don have permission'
            ], 402);
        }
    }

    // Get All Leads Sources
    public function allStatus(Request $request): \Illuminate\Http\JsonResponse
    {

        $Statuses = LeadStatus::all();

        return response()->json([
            'data' => [
                'Statuses' => $Statuses
            ]

        ], 200);


    }
}

<?php

namespace Modules\Leads\App\Http\Controllers\admin;

use Modules\Files\App\Http\Controllers\Controller;
use App\Jobs\User\ProcessCreateUserByOperator;
use Modules\Leads\App\Models\LeadSources;
use Modules\Leads\App\Models\LeadStatus;
use Modules\User\App\Models\Portal\PortalUser;
use Modules\User\App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeadSourceController extends Controller
{

    // Create  Source
    public function createSource(Request $request): \Illuminate\Http\JsonResponse
    {

        $operator = Auth::user();

        if ($operator->can('users:create')) {

            $validate = Validator::make($request->all(), [
                'source_name' => ['required', 'string', 'unique:lead_sources'],
                'active' => ['required'],
            ]);

            // Validator Has Error
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()->first()], 400);
            }


            $source = new LeadSources([
                'source_name' => $request->source_name,
                'active' => $request->active,

            ]);

            $source->save();


            return response()->json([
                'message' => 'Source Name has created successful',
            ], 201);


            // If admin dos not permission to create user
        } else {
            return response()->json([
                'message' => 'You don have permission'
            ], 402);
        }
    }

    // Get All Leads Sources
    public function allSources(Request $request): \Illuminate\Http\JsonResponse
    {

        $sources = LeadSources::all();

        return response()->json([
            'data' => [
                'sources' => $sources
            ]

        ], 200);


    }
}

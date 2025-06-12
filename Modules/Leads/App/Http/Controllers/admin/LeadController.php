<?php

namespace Modules\Leads\App\Http\Controllers\admin;

use Modules\Files\App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Modules\Auth\App\Services\CreateUserByAdminService;
use Modules\Leads\App\Services\ConvertLeadToUserService;
use Modules\Leads\App\Services\CreateLeadService;
use Modules\Leads\App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @property $CreateUserByAdminService
 */
class LeadController extends Controller
{

    private CreateLeadService $createLeadService;
    public ConvertLeadToUserService $convertLeadToUserService;


    public function __construct(
        CreateLeadService $createLeadService,
        ConvertLeadToUserService $convertLeadToUserService

    ) {
        $this->createLeadService = $createLeadService;
        $this->convertLeadToUserService = $convertLeadToUserService;
    }

    // Create  Lead
    public function createLead(Request $request): \Illuminate\Http\JsonResponse
    {

        $operator = Auth::user();

        if ($operator->can('users:create')) {

            $validate = Validator::make($request->all(), [
                'staff_id' => ['required'],
                'source_id' => ['required'],
                'status_id' => ['required'],
                'email' => ['string', 'unique:leads', 'unique:users'],
                'mobile_number' => ['unique:leads','unique:users'],
                'active' => ['required'],
            ]);

            // Validator Has Error
            if ($validate->fails()) {
                return response()->json(['message' => $validate->errors()->first()], 400);
            }

            // Send Validated Data to Service
            $this->createLeadService->createLead($request->all());


            return response()->json([
                'data' => [
                    'message' => 'Lead has created successful',
                ]
            ], 201);


            // If admin dos not permission to create user
        } else {
            return response()->json([
                'message' => 'You don have permission'
            ], 402);
        }
    }

    // Get All Leads
    public function allLeads(Request $request): \Illuminate\Http\JsonResponse
    {

        $leads = Lead::all();

        return response()->json([
            'data' => [
                'leads' => $leads
            ]

        ], 200);


    }


    // Convert Lead to user
    public function convertLeadToUser(Request $request): \Illuminate\Http\JsonResponse
    {

        $operator = Auth::user();

        if ($operator->can('users:create')) {


            // Send Validated Data to Service
            $this->convertLeadToUserService->convertLead($request->all());


            return response()->json([
                'data' => [
                    'message' => 'user has created successful',
                ]
            ], 201);


            // If admin dos not permission to create user
        } else {
            return response()->json([
                'message' => 'You don have permission'
            ], 402);
        }
    }
}

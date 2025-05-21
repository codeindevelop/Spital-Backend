<?php

namespace Modules\Training\App\Http\Controllers\admin\student;

use App\Http\Controllers\Controller;


use Modules\Training\App\Models\Student\Student;


use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\User\App\Models\User;

class StudentAdminController extends Controller
{

    // Get All Students List
    public function getAllStudentsList(): JsonResponse
    {
        $operator = Auth::user();


        if ($operator->can('student:view')) {

            $students = User::has('student')->with('roles')->with('permissions')->with('personalInfos')->get();

            return response()->json([
                'data' => [
                    'students' => $students,
                    'studentsCounts' => $students->count(),
                ]

            ], 200);
        } else {
            return response()->json([
                'message' => 'You don have permission to see all students'
            ], 402);
        }
    }

    // Get All Students List
    public function createStudentByOperator(Request $request): JsonResponse
    {
        $operator = Auth::user();

        if ($operator->can('student:create')) {

            $validate = Validator::make($request->all(), [

                'user_id' => ['required', 'unique:students'],
                'level_id' => ['required'],
                'status' => ['required', 'string'],
                'active' => ['required'],

            ]);
            if ($validate->fails()) {
                // Check user email has not exist
                $user_id = Student::where('user_id', $request->user_id)->first();

                if ($user_id) {
                    return response()->json(
                        [
                            'error' => 'user_id has Exist'
                        ],
                        401
                    );
                }
            }


            //  student:create in db
            $student = new Student([
                'user_id' => $request->user_id,
                'student_group_id' => $request->student_group_id,
                'level_id' => $request->level_id,
                'status' => $request->status,
                'profile_img' => $request->profile_img,
                'cover_img' => $request->cover_img,
                'about_student' => $request->about_student,
                'company_name' => $request->company_name,
                'location_name' => $request->location_name,
                'instagram_id' => $request->instagram_id,
                'x_id' => $request->x_id,
                'github_id' => $request->github_id,
                'gitlab_id' => $request->gitlab_id,
                'bitbucket_id' => $request->bitbucket_id,
                'website_url' => $request->website_url,
                'job_name' => $request->job_name,
                'description' => $request->description,
                'referer_code' => $request->referer_code,
                'referer_name' => $request->referer_name,
                'referer_mobile_number' => $request->referer_mobile_number,
                'referer_email' => $request->referer_email,
                'active' => $request->active,

                'register_datetime' => Carbon::now(),

            ]);

            $student->save();

            $user = User::Where('id', $student->user_id)->first();
            $send_wellcom_sms = $request->send_wellcom_sms;
            $send_wellcom_email = $request->send_wellcom_email;

//            ProcessCreateStudentByOperator::dispatchAfterResponse($user, $student, $operator, $send_wellcom_sms,
//                $send_wellcom_email);


            return response()->json([
                'data' => [
                    'message' => 'student has ben Created successful!',
                    'student' => $student,
                ]


            ], 201);
        } // If admin dos not permission to student:create
        else {
            return response()->json([
                'message' => 'You don have permission to Create student',

            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    // Get All Students List
    public function getStudentById(Request $request): JsonResponse
    {

        $operator = Auth::user();

        if ($operator->can('student:view')) {

            $student = Student::where('id', $request->student_id)->first();
            // if students has exist
            if ($student) {

                $user = User::where('id',
                    $student->user_id)->with('roles')->with('permissions')->with('personalInfos')->get();
                return response()->json([
                    'data' => [
                        'student' => $student,
                        'user_data' => $user,
                    ]

                ], 200);
                // if students has not exist
            } else {
                return response()->json([
                    'data' => [
                        'message' => 'student not found with this id '
                    ]

                ], 404);
            }
            // $student = Student::where('user_id',$id)->first();


        } else {
            return response()->json([
                'data' => [
                    'message' => 'You don have permission '
                ]

            ], 401);
        }
    }


    public function editStudentById(Request $request): JsonResponse
    {
        // Get Current User
        $user = Auth::user();

        // Check user permission to update student
        if ($user->hasPermissionTo('student:edit')) {


            $Student = Student::findOrFail($request->student_id);

            $Student->update($request->all());

            return response()->json([
                'data' => [
                    'message' => 'Student has ben Updated!',
                ]
            ], 202);

            // If user has no permission to edit category
        } else {

            return response()->json([
                'message' => 'You dont have permission to edit categories'
            ], 401);
        }
    }

    public function deleteStudentById(Request $request): JsonResponse
    {
        // Get Current User
        $user = Auth::user();

        // Check user permission to update student group
        if ($user->hasPermissionTo('student:delete')) {


            $student = Student::where('id', $request->student_id)->first();
            if ($student) {

                $student->delete();

                return response()->json([
                    'data' => [
                        'message' => 'Student  has ben delete!',

                    ]

                ], 202);
            } else {
                return response()->json([
                    'data' => [
                        'message' => 'Student not found',

                    ]

                ], 404);
            }
            // If user has no permission to edit category
        } else {

            return response()->json([
                'message' => 'You dont have permission'
            ], 401);
        }
    }

    public function restoreTrashedStudent(Request $request): JsonResponse
    {
        // Get Current User
        $user = Auth::user();

        // Check user permission to restore student
        if ($user->hasPermissionTo('student:delete')) {


            // Check Deleted student
            $student = User::withTrashed()->find($request->student_id);

            $student->restore();

            return response()->json([
                'message' => 'student has ben restored successful'
            ], 200);
        } else {
            return response()->json([
                'message' => 'You dont have permission'
            ], 500);
        }
    }
}

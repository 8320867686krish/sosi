<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Projects;
use App\Models\ProjectSurveyor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\ValidationException;
use Throwable;
use  App\Jobs\SendVerificationEmail;
use App\Models\AppUserVerify;
use App\Models\CheckImage;
use App\Models\Deck;
use App\Models\Checks;
use App\Models\ChecksQrCodePair;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function sendResponse($result, $message, $arrkey = 'data')
    {
        $response = [
            'error' => true,
            'message' => $message,
        ];

        if (!empty($result)) {
            $response[$arrkey] = $result;
        }

        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = null)
    {
        $response = [
            'error' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response);
    }


    // User Api
    public function register(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'email' => ['unique:' . User::class],
            ], [
                'email.unique' => 'Already this user registered'
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $requestData = [
                "name" => $request->input('first_name'),
                "last_name" => $request->input('last_name'),
                "email" => $request->input('email'),
                "password" => Hash::make($request->input('password')),
                "isApp" => 1,
            ];

            $role = Role::where('name', 'Surverer')->pluck('id')->first();

            $user = User::create($requestData);

            $user->assignRole([$role]);

            $token = $user->createToken('authToken')->plainTextToken;

            return $this->sendResponse($token, 'User register successfully.', 'token');
        } catch (Throwable $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            // Validate request data
            $isOtpSend = false;
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Find the user by email
            $user = User::where('email', $validatedData['email'])->first();

            // Check if user exists and password is correct
            if (!$user || !Hash::check($validatedData['password'], $user->password)) {
                return response()->json(['isStatus' => false, 'message' => 'The provided credentials are incorrect.']);
            }

            // Check if the user is verified
            if (!$user->isVerified) {
                return response()->json(['isStatus' => false, 'message' => 'This user is not verified. Please contact the administrator.']);
            }

            if (!$user->hasPermissionTo('APP.access')) {
                return response()->json(['isStatus' => false, 'message' => 'User does not have permission for app access.']);
            }

            $existingToken = PersonalAccessToken::where('tokenable_id', $user->id)->first();

            if ($existingToken) {
                $isOtpSend = true;
                $code = random_int(100000, 999999);
                $details['email'] = $request['email'];
                $details['code'] = $code;
                dispatch(new SendVerificationEmail($details));
            }
            // Check if user is already logged in from another device

            // Create a new token
            $token = $user->createToken('ApiToken')->plainTextToken;
            if ($isOtpSend == true) {
                AppUserVerify::create([
                    'token' => $token,
                    'code' => $code
                ]);
            }

            $userData = $user->makeHidden(['roles', 'permissions', 'email_verified_at', 'firebase_token', 'created_at', 'updated_at'])->toArray();

            foreach ($userData as $key => $value) {
                $userData[$key] = $value ?? ''; // Replace null with empty string
            }


            return response()->json([
                'isStatus' => true,
                'message' => 'User login successful.',
                'isOtpSend' => $isOtpSend,
                'user' =>   $userData,
                'token' => $token,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['isStatus' => false, 'error' => $e->getMessage()]);
        } catch (Throwable $th) {
            print_r($th->getMessage());
            // Token creation failed
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }

    public function refreshSanctumToken(Request $request)
    {
        try {
            $user = Auth::user(); // Get the authenticated user
            Auth::user()->tokens()->delete();
            $token = $user->createToken('ApiToken')->plainTextToken; // Create a new token for the user

            return response()->json([
                'isStatus' => true,
                'message' => 'Token refreshed successfully',
                'token' => $token
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'isStatus' => false,
                'message' => 'Failed to refresh token'
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['isStatus' => false, 'message' => 'The provided current password does not match our records.']);
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return response()->json(['isStatus' => true, 'message' => 'Password changed successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['isStatus' => false, 'message' => $e->validator->errors()->first()]);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $isStatus = Password::sendResetLink($request->only('email'));

            if ($isStatus === Password::RESET_LINK_SENT) {
                return response()->json(['isStatus' => true, 'message' => __('passwords.sent')], Response::HTTP_OK);
            } else {
                return response()->json(['isStatus' => false, 'message' => __('passwords.user')], Response::HTTP_BAD_REQUEST);
            }
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => __('An error occurred while processing your request.')], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required|string',
                'new_password' => 'required|string|min:8',
                'confirm_new_password' => 'required|string|same:new_password',
            ]);

            $isStatus = Password::reset($request->only('email', 'token', 'new_password', 'confirm_new_password'), function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => null,
                ])->save();
            });

            if ($isStatus == Password::PASSWORD_RESET) {
                return response()->json(['message' => 'Password reset successfully.']);
            } else {
                return response()->json(['message' => 'Password reset failed.'], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function logout()
    {
        try {
            // Validate the request
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'isStatus' => false,
                    'message' => 'User not authenticated.',
                ], 401);
            }

            // Revoke user tokens
            $user->tokens()->delete();

            // Log the logout activity
            Log::info('User logged out successfully.', ['user_id' => $user->id]);

            // Log the user out
            Auth::guard('web')->logout(); // Assuming 'web' guard is used

            // Return success response
            return response()->json([
                'isStatus' => true,
                'message' => 'Logged out successfully.',
            ]);
        } catch (Throwable $th) {
            // Log the error
            Log::error('Logout error: ' . $th->getMessage());

            // Return error response
            return response()->json([
                'isStatus' => false,
                'message' => 'An error occurred while processing your request.',
            ], 500);
        }
    }

    public function getProjectList()
    {
        try {
            $user = Auth::user();

            $currentUserRoleLevel = $user->roles->first()->level;

            if ($currentUserRoleLevel == 1 || $currentUserRoleLevel == 2) {
                $project = Projects::select('id','client_id','ship_name','imo_number','project_no','image')->with('client:id,manager_name');
            } else {
                $project = $user->projects()->select('projects.id', 'client_id', 'ship_name', 'imo_number', 'project_no', 'image')
                ->with('client:id,manager_name')->where('isExpire', 0);
            }

            $project = $project->get();
            $modifiedProjects = [];

            if ($project->count() > 0) {
                $modifiedProjects = $project;
            }

            return response()->json(['isStatus' => true, 'message' => 'Project list retrieved successfully.', 'projectList' => $modifiedProjects]);
        } catch (Throwable $th) {
            echo $th->getMessage();
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.', 'projectList' => []]);
        }
    }

    public function getShipDetail($project_id)
    {
        try {
            $project = Projects::with('client:id,manager_name,manager_address,owner_name,owner_address')->where('id', $project_id)->first()->toarray();
            foreach ($project as $key => $value) {
                if ($value === null) {
                    $project[$key] = '';
                }
            }
            $project['survey_date'] = (@$project['survey_date']) ? @$project['survey_date'] : "";
            if ($project) {
                return response()->json(['isStatus' => true, 'message' => 'Project detail retrieved successfully.', 'shipParticular' => $project]);
            }
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.', 'shipParticular' => (object) []]);
        }
    }

    // Surveyors Api
    public function getProjectSurveyors($project_id)
    {
        try {
            $surverorDetails = projects::where('id', $project_id)->first()->toArray();
            foreach ($surverorDetails as $key => $value) {
                if ($value === null) {
                    $surverorDetails[$key] = '';
                }
            }
            return response()->json(['isStatus' => true, 'message' => 'surveyor details retrieved successfully.', 'surveyorDetails' => $surverorDetails]);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.', 'surveyorDetails' => (object) []]);
        }
    }

    public function addProjectSurveyors(Request $request)
    {
        try {
            $inputData = $request->input();
            $projectId = $inputData['id'];
            // Update the project record with the provided data
            Projects::where(['id' => $projectId])->update($inputData);
            return response()->json(['isStatus' => true, 'message' => 'successfully save details !!']);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }

    public function verifyCode(Request $request)
    {
        $post = $request->input();
        $token = $request->bearerToken();
        $otpVerify = AppUserVerify::where(['token' => $token, 'code' => $post['code']])->first();
        if ($otpVerify) {
            $otpVerify->delete();
            return response()->json(['isStatus' => true, 'message' => 'successfully verified!!']);
        } else {
            return response()->json(['isStatus' => false, 'message' => 'enter valid code.']);
        }
    }

    public function editCheck(Request $request)
    {
        try {

            // $validator = Validator::make($request->all(), [
            //     'project_id' => 'required|exists:projects,id',
            //     'deck_id' => 'required|exists:decks,id',
            //     'type' => 'required',
            //     'position_left' => 'required',
            //     'position_top' => 'required'
            // ]);

            // $validator = Validator::make($request->all(), [
            //     'project_id' => 'required|exists:projects,id',
            //     'deck_id' => 'required|exists:decks,id',
            //     'apiType' => 'required|in:check,location', // Ensure apiType is required and has either 'check' or 'location' value
            //     'type' => 'required_if:apiType,check', // Require type only if apiType is 'check'
            //     'position_left' => 'required_if:apiType,location', // Require position_left only if apiType is 'location'
            //     'position_top' => 'required_if:apiType,location', // Require position_top only if apiType is 'location'
            // ]);

            $validator = Validator::make($request->all(), [
                'project_id' => 'required|exists:projects,id',
                'deck_id' => 'required|exists:decks,id',
                'position_left' => 'required', // Require position_left only if apiType is 'location'
                'position_top' => 'required', // Require position_top only if apiType is 'location'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $projectId = $request->input('project_id');
            $deckId = $request->input('deck_id');

            // Eager load project and deck to reduce database queries
            $project = Projects::find($projectId);

            // Check if project and deck exist
            if (!$project) {
                return response()->json(['isStatus' => false, 'message' => 'Project not found.']);
            }

            $deckExists = Deck::where([
                ['id', $deckId],
                ['project_id', $projectId]
            ])->exists();

            if (!$deckExists) {
                return response()->json(['isStatus' => false, 'message' => 'Deck not found.']);
            }

            $id = $request->input('id');
            $inputData = $request->except('id');
            //Determine if it's created by the app side.
            $inputData['isApp'] = 1;

            Checks::updateOrCreate(['id' => $id], $inputData);

            $message = empty($id) ? "Check added successfully" : "Check updated successfully";

            return response()->json(['isStatus' => true, 'message' => $message]);
        } catch (ValidationException $e) {
            return response()->json(['isStatus' => false, 'message' => $e->validator->errors()->first()]);
        } catch (Throwable $th) {
            Log::error('Error occurred while processing addNewCheck API: ' . $th->getMessage());
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }
    public function addCheck(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'project_id' => 'required|exists:projects,id',
                'deck_id' => 'required|exists:decks,id',
                'position_left' => 'required_if:apiType,location', // Require position_left only if apiType is 'location'
                'position_top' => 'required_if:apiType,location', // Require position_top only if apiType is 'location'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $projectId = $request->input('project_id');
            $deckId = $request->input('deck_id');
            $inputData = $request->input();
            $inputData['isApp'] = 1;
            $projectDetail = Projects::with(['client' => function ($query) {
                $query->select('id', 'manager_initials'); // Replace with the fields you want to select
            }])->withCount('checks')->find($inputData['project_id']);
            $lastCheck = Checks::where('project_id', $inputData['project_id'])
                ->latest()
                ->first();
            if (!$lastCheck) {
                $projectCount = "1001";
            } else {
                $projectCount = $lastCheck['initialsChekId'] + (1);
            }
            $name = "sos" . $projectDetail['client']['manager_initials'] . $projectCount;
            $inputData['name'] = $name;
            $inputData['initialsChekId'] =  $projectCount;
            // Eager load project and deck to reduce database queries
            $project = Projects::find($projectId);

            // Check if project and deck exist
            if (!$project) {
                return response()->json(['isStatus' => false, 'message' => 'Project not found.']);
            }

            $deckExists = Deck::where([
                ['id', $deckId],
                ['project_id', $projectId]
            ])->exists();

            if (!$deckExists) {
                return response()->json(['isStatus' => false, 'message' => 'Deck not found.']);
            }

            $checkAdd =  Checks::create($inputData);

            $checkId = $checkAdd->id;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . rand(10, 99) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/pdf/' .  $projectId), $imageName);
                $checkData['image'] = $imageName;
                $checkData['check_id'] = $checkId;
                CheckImage::create($checkData);
            }

            $message = "Check added successfully";

            return response()->json(['isStatus' => true, 'message' => $message]);
        } catch (ValidationException $e) {
            return response()->json(['isStatus' => false, 'message' => $e->validator->errors()->first()]);
        } catch (Throwable $th) {
            Log::error('Error occurred while processing addNewCheck API: ' . $th->getMessage());
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }
    // project decks
    public function getDeckList($project_id)
    {
        try {
            $decks = Deck::withCount('checks')->where('project_id', $project_id)->get();
            return response()->json(['isStatus' => true, 'message' => 'Project deck list retrieved successfully.', 'projectDeckList' => $decks]);
        } catch (Throwable $th) {
            print_r($th->getMessage());
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }

    public function getCheckList(Request $request)
    {
        try {


            $deckId = $request->input('deckId');
            $filterValue = $request->input('filterValue');
            $deck = Deck::find($deckId);

            if (!$deck) {
                return response()->json(['isStatus' => false, 'message' => 'Deck not found.']);
            }

            $checks = Checks::with(['check_image' => function ($query) {
                $query->latest()->take(1); // Order by insertion timestamp and take only the latest image
            }])->where('deck_id', $deckId);

            if($filterValue == 'unCompleted'){
                $checks = $checks->where('isCompleted',0);
            }
            if($filterValue == 'completed'){
                $checks = $checks->where('isCompleted',1);
            }
            $checks = $checks->get();
            return response()->json(['isStatus' => true, 'message' => 'Project checks list retrieved successfully.', 'projectChecks' => $checks]);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }

    public function getCheckDetail($checkId)
    {
        try {
            $checks = Checks::with('deck')->find($checkId);

            if (!$checks) {
                return response()->json(['isStatus' => false, 'message' => 'Check not found.']);
            }

            $deckImage = $checks['deck']['image'];

            $locationDetails = array_merge(
                $checks->only(['compartment', 'position', 'sub_position', 'position_left', 'position_top', 'project_id', 'deck_id']),
                ['deck_image' => $deckImage]
            );
            $checkDetails = $checks->only(['component', 'equipment', 'name', 'type', 'suspected_hazmat', 'remarks', 'project_id', 'deck_id', 'pairWitthTag']);

            return response()->json(['isStatus' => true, 'message' => 'check details retrieved successfully.', 'checkDetails' => $checkDetails, 'locationDetails' => $locationDetails]);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }

    public function deleteCheck($id)
    {
        try {
            $check = Checks::find($id);

            if (!$check) {
                return response()->json(['isStatus' => false, 'message' => 'Check not found.']);
            }

            $check->delete();

            return response()->json(['isStatus' => true, 'message' => 'Check deleted successfully.']);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }

    public function getCheckImgList($check_id)
    {
        try {
            $checkImgs = CheckImage::where('check_id', $check_id)->get();
            $chkPairData = Checks::find($check_id);
            $chkPair =  $chkPairData['pairWitthTag'];
            if(@$chkPair){
                $data[]['pairWitthTag'] =  $chkPair;

            }else{
                $data = [];

            }
            $mainPath = url("public/images/pdf/{$chkPairData['project_id']}") . "/";
            return response()->json(['isStatus' => true, 'message' => 'Check images retrieved successfully.', 'mainPath' => $mainPath, 'checkImagesList' => $checkImgs,'chkPair' => $data]);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }

    public function addCheckImg(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'check_id' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $check = Checks::find($request->input('check_id'));

            if (!$check) {
                return response()->json(['isStatus' => false, 'message' => 'Check not found.']);
            }

            $inputData = $request->only(['check_id']);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . rand(10, 99) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/pdf/' . $check['project_id']), $imageName);
                $inputData['image'] = $imageName;
                $inputData['project_id'] = $check['project_id'];
                $check->isCompleted = 1;
                $check->save();
            }

            CheckImage::create($inputData);

            return response()->json(['isStatus' => true, 'message' => 'Check image added successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['isStatus' => false, 'message' => $e->validator->errors()->first()]);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }
    public function qrCodePair(Request $request){
        $checkId = $request->input('checkId');
        $pairWitthTag = $request->input('pairWitthTag');

        try {
            $check = Checks::find($checkId);

            if (!$check) {
                return response()->json(['isStatus' => false, 'message' => 'Check not found.']);
            }
            $check->pairWitthTag = $pairWitthTag;
            $check->save();
            return response()->json(['isStatus' => true, 'message' => 'Successfully creted qr code pair.']);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }
    public function deleteCheckImg($id)
    {
        try {
            $checkImg = CheckImage::find($id);

            if (!$checkImg) {
                return response()->json(['isStatus' => false, 'message' => 'Check image not found.']);
            }

            $path = public_path("images/checks/{$checkImg->check_id}/{$checkImg->image}");

            if (file_exists($path)) {
                unlink($path);
            }

            $checkImg->delete();

            return response()->json(['isStatus' => true, 'message' => 'Check image deleted successfully.']);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }
    public function updatePairWithTag($id)
    {
        try {
            $check = Checks::find($id);

            if (!$check) {
                return response()->json(['isStatus' => false, 'message' => 'Check not found.']);
            }

            $check->pairWitthTag = NULL;
            $check->save();
            return response()->json(['isStatus' => true, 'message' => 'PairWithTag deleted successfully.']);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }
    public function tableStruture(){
        $projects = DB::select('describe projects');
        $clients = DB::select('describe clients');
        $decks = DB::select('describe decks');
        $checks = DB::select('describe checks');
        $check_has_images = DB::select('describe check_has_images');
        return response()->json(['isStatus' => true, 'message' => 'table strture.','projects'=>$projects,'clients' => $clients,'decks' => $decks,'checks' => $checks,'check_has_images' => $check_has_images]);
    }
   
}

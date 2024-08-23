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
use App\Models\CheckHasHazmat;
use App\Models\CheckImage;
use App\Models\Deck;
use App\Models\Checks;
use App\Models\ChecksQrCodePair;
use App\Models\Hazmat;
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
                $isOtpSend = false;
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
    public function saveLocation(Request $request)
    {
        try {
            $user = Auth::user();
            $user->update([
                'location' => $request->location,
            ]);
            return response()->json(['isStatus' => true, 'message' => 'address saved successfully.']);
        } catch (ValidationException $e) {
            return response()->json(['isStatus' => false, 'message' => $e->validator->errors()->first()]);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
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
                $projects = Projects::select(
                    'projects.*',
                    'clients.manager_name',
                    'clients.owner_name',
                    'clients.owner_address',
                    'clients.manager_address'
                )
                    ->leftJoin('clients', 'projects.client_id', '=', 'clients.id')
                    ->get();
            } else {
                $projects = Projects::select(
                    'projects.*',
                    'clients.manager_name',
                    'clients.owner_name',
                    'clients.owner_address',
                    'clients.manager_address'
                )
                    ->leftJoin('clients', 'projects.client_id', '=', 'clients.id')
                    ->leftJoin('project_teams', 'projects.id', '=', 'project_teams.project_id')
                    ->where('project_teams.isExpire', 0)
                    ->where('project_teams.user_id', $user->id)
                    ->whereDate('project_teams.assign_date', '<=', date('Y-m-d'))
                    ->get();
            }

            $modifiedProjects = [];

            if ($projects->count() > 0) {
                $modifiedProjects = $projects;
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
                $project['manager_name'] = $project['client']['manager_name'];
                $project['manager_address'] = $project['client']['manager_address'];
                $project['owner_name'] = $project['client']['owner_name'];
                $project['owner_address'] = $project['client']['owner_address'];
                unset($project['client']);
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

            $messages = [
                'project_id.required' => 'The project is required.',
                'project_id.exists' => 'The selected project is invalid.',
                'deck_id.required' => 'The deck is required.',
                'deck_id.exists' => 'The selected deck is invalid.',
                'position_left.required' => 'The position left field is required',
                'position_top.required' => 'The position top field is required',
            ];

            $validator = Validator::make($request->all(), [
                'project_id' => 'required|exists:projects,id',
                'deck_id' => 'required|exists:decks,id',
                'position_left' => 'required', // Require position_left only if apiType is 'location'
                'position_top' => 'required', // Require position_top only if apiType is 'location'
            ], $messages);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $id = $request->input('id');
            $inputData = $request->input();
            // $suspected_hazmat = $request->input('suspected_hazmat');
            unset($inputData['suspected_hazmat']);

            Checks::where('id', $id)->update($inputData);

            if ($request->filled('suspected_hazmat')) {

                $suspectedHazmat = explode(',', $request->input('suspected_hazmat'));
                $logArray = array_map('trim', $suspectedHazmat);

                $hazmatIds = Hazmat::whereIn('name', $logArray)->pluck('id')->toArray();

                CheckHasHazmat::where([
                    "project_id" => $inputData['project_id'],
                    "check_id" => $inputData['id'],
                ])->whereNotIn('hazmat_id', $hazmatIds)->delete();

                foreach ($hazmatIds as $hazmatId) {
                    $hazmatData = [
                        "project_id" => $inputData['project_id'],
                        "check_id" => $inputData['id'],
                        "hazmat_id" => $hazmatId,
                        "type" => "Unknown",
                        "check_type" => $inputData['type']
                    ];
                    $checkhasData = CheckHasHazmat::where('hazmat_id', $hazmatId)->where('check_id', $inputData['id'])->first();
                    if (!@$checkhasData) {
                        CheckHasHazmat::create($hazmatData);
                    }
                }
            }

            return response()->json(['isStatus' => true, 'message' => "Check updated successfully"]);
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

            $messages = [
                'project_id.required' => 'The project is required.',
                'project_id.exists' => 'The selected project is invalid.',
                'deck_id.required' => 'The deck is required.',
                'deck_id.exists' => 'The selected deck is invalid.',
                'position_left.required_if' => 'The position left field is required when API type is location.',
                'position_top.required_if' => 'The position top field is required when API type is location.',
            ];

            $validator = Validator::make($request->all(), [
                'project_id' => 'required|exists:projects,id',
                'deck_id' => 'required|exists:decks,id',
                'position_left' => 'required_if:apiType,location',
                'position_top' => 'required_if:apiType,location',
            ], $messages);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $projectId = $request->input('project_id');
            $inputData = $request->input();
            $inputData['isApp'] = 1;
            $projectDetail = Projects::find($inputData['project_id']);
            $lastCheck = Checks::where('project_id', $projectId)->latest()->first();
            if (!$lastCheck) {
                $projectCount = "0";
            } else {
                $projectCount = $lastCheck['initialsChekId'];
            }
            $name = $projectDetail['ship_initials'] . 'VSC#' . str_pad($projectCount + 1, 3, 0, STR_PAD_LEFT);

            $inputData['name'] = $name;
            $inputData['initialsChekId'] = str_pad($projectCount + 1, 3, 0, STR_PAD_LEFT);
            if (!$lastCheck) {
                $projectCount = "001";
            } else {
                $projectCount = $lastCheck['initialsChekId'] + (1);
            }
            $name = $projectDetail['ship_initials'] . "VSC#" . $projectCount;
            $inputData['name'] = $name;
            $inputData['initialsChekId'] =  $projectCount;

            $checkAdd =  Checks::create($inputData);

            $checkId = $checkAdd->id;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . rand(10, 99) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path(env('IMAGE_COMMON_PATH', "images/projects/") .  $projectId), $imageName);
                $checkData['image'] = $imageName;
                $checkData['project_id'] = $inputData['project_id'];
                $checkData['check_id'] = $checkId;
                CheckImage::create($checkData);
            }

            if (!empty($request->input('suspected_hazmat'))) {
                $suspectedHazmat = explode(', ', $request->input('suspected_hazmat'));

                foreach ($suspectedHazmat as $hazmat) {
                    $hazmatId = Hazmat::where('name', $hazmat)->first();

                    $hazmatData = [
                        "project_id" => $inputData['project_id'],
                        "check_id" => $checkId,
                        "hazmat_id" => $hazmatId->id,
                        "type" => "Unknown",
                        "check_type" => $inputData['type']
                    ];

                    CheckHasHazmat::create($hazmatData);
                }
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

            if ($filterValue == 'unCompleted') {
                $checks = $checks->where('isCompleted', 0);
            }
            if ($filterValue == 'completed') {
                $checks = $checks->where('isCompleted', 1);
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
            $checks = Checks::with(['hazmats' => function ($query) {
                $query->with('hazmat:id,name'); // Eager load hazmat with only id, name, and image columns
            }])->with('deck')->find($checkId);

            if (!$checks) {
                return response()->json(['isStatus' => false, 'message' => 'Check not found.']);
            }

            if ($checks->hazmats->count() > 0) {
                $hazmatNames = [];

                foreach ($checks->hazmats as $hazmat) {
                    $hazmatNames[] = $hazmat->hazmat->name;
                }

                $checks->suspected_hazmat = implode(', ', $hazmatNames);
                unset($checks->hazmats);
            } else {
                $checks->suspected_hazmat = null;
            }

            $checkDetails = $checks;
            $checkDetails['deckImage'] = $checks['deck']['image'];
            unset($checkDetails['deck']);

            return response()->json(['isStatus' => true, 'message' => 'check details retrieved successfully.', 'checkDetails' => $checkDetails]);
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
            if (@$chkPair) {
                $data[]['pairWitthTag'] =  $chkPair;
            } else {
                $data = [];
            }
            return response()->json(['isStatus' => true, 'message' => 'Check images retrieved successfully.', 'checkImagesList' => $checkImgs, 'chkPair' => $data]);
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
                $imagePath = $image->getPathName();

                // Get the dimensions, considering EXIF orientation
                if (function_exists('exif_read_data')) {
                    $exif = @exif_read_data($imagePath);
                    if ($exif && isset($exif['Orientation'])) {
                        $orientation = $exif['Orientation'];
                        // Swap width and height if necessary
                        if (in_array($orientation, [6, 8])) {
                            list($height1, $width1) = getimagesize($imagePath);
                        } else {
                            list($width1, $height1) = getimagesize($imagePath);
                        }
                    } else {
                        list($width1, $height1) = getimagesize($imagePath);
                    }
                } else {
                    list($width1, $height1) = getimagesize($imagePath);
                }

                // Generate a unique image name
                $imageName = time() . rand(10, 99) . '.' . $image->getClientOriginalExtension();

                // Resize the image if it's taller than it is wide
                if ($height1 > $width1) {
                    list($width, $height) = getimagesize($imagePath);

                    if (function_exists('exif_read_data')) {
                        $exif = @exif_read_data($imagePath);
                        $orientation = isset($exif['Orientation']) ? $exif['Orientation'] : 1;

                        switch ($orientation) {
                            case 3:
                                $source_image = imagecreatefromjpeg($imagePath);
                                $source_image = imagerotate($source_image, 180, 0);
                                break;

                            case 6:
                                $source_image = imagecreatefromjpeg($imagePath);
                                $source_image = imagerotate($source_image, -90, 0);
                                list($width, $height) = [$height, $width]; // Swap dimensions
                                break;

                            case 8:
                                $source_image = imagecreatefromjpeg($imagePath);
                                $source_image = imagerotate($source_image, 90, 0);
                                list($width, $height) = [$height, $width]; // Swap dimensions
                                break;

                            default:
                                $source_image = imagecreatefromjpeg($imagePath);
                                break;
                        }
                    } else {
                        $source_image = imagecreatefromjpeg($imagePath);
                    }

                    $new_size = max($width, $height);

                    $resized_image = imagecreatetruecolor($new_size, $new_size);

                    $white = imagecolorallocate($resized_image, 255, 255, 255);

                    imagefill($resized_image, 0, 0, $white);

                    $x_offset = ($new_size - $width) / 2;
                    $y_offset = ($new_size - $height) / 2;

                    imagecopyresampled($resized_image, $source_image, $x_offset, $y_offset, 0, 0, $width, $height, $width, $height);

                    if (!file_exists(public_path(env('IMAGE_COMMON_PATH', "images/projects/") . $check['project_id']))) {
                        mkdir(public_path(env('IMAGE_COMMON_PATH', "images/projects/") . $check['project_id']), 0755, true);
                    }

                    imagejpeg($resized_image, public_path(env('IMAGE_COMMON_PATH', "images/projects/") . $check['project_id']) . "/" . $imageName);

                    imagedestroy($resized_image);
                    imagedestroy($source_image);
                } else {
                    // Move the original image without resizing
                    $image->move(public_path(env('IMAGE_COMMON_PATH', "images/projects/") . $check['project_id']), $imageName);
                }

                // Store the image name and update the project status
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
            Log::info("Check Image Error", ["error" => $th->getMessage()]);
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }
    public function qrCodePair(Request $request)
    {
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

            $path = public_path(env('IMAGE_COMMON_PATH', "images/projects/") . $checkImg->project_id . "/" . $checkImg->image);

            if (file_exists($path)) {
                unlink($path);
            }

            $checkImg->delete();
            $checkData = Checks::withCount('check_image')->find($checkImg['check_id']);

            // Check if $checkData has no associated images
            if ($checkData->check_image_count === 0) {
                // Perform your update here
                // For example, you can update a specific attribute or perform any desired action
                $checkData->update(['isCompleted' => 0]);
            }

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
    public function tableStruture()
    {
        $projects = DB::select('describe projects');
        $clients = DB::select('describe clients');
        $hazmats = DB::select('describe hazmats');
        foreach ($clients as $clientField) {


            if ($clientField->Field == 'manager_name' || $clientField->Field == 'manager_address' || $clientField->Field == 'owner_name' || $clientField->Field == 'owner_address')
                $projects[] = $clientField;
        }

        $decks = DB::select('describe decks');
        $checks = DB::select('describe checks');
        $checks = array_values($checks);

        foreach ($hazmats as $hazmatField) {
            if ($hazmatField->Field == 'name') {
                $checks[] = $hazmatField;
                break; // No need to continue loop once found
            }
        }
        $in = array_key_last($checks);
        $checks[$in]->Field = 'suspected_hazmat';
        // foreach ($checks as &$check) {
        //     if ($check->Field === 'name') {
        //         $check->Field = 'suspected_hazmats';
        //     }
        // }
        $check_has_images = DB::select('describe check_has_images');
        $projects = $this->modifyTypeValues($projects);
        $clients = $this->modifyTypeValues($clients);
        $decks = $this->modifyTypeValues($decks);
        $checks = $this->modifyTypeValues($checks);
        $check_has_images = $this->modifyTypeValues($check_has_images);
        return response()->json(['isStatus' => true, 'message' => 'table strture.', 'projects' => $projects, 'decks' => $decks, 'checks' => $checks, 'check_has_images' => $check_has_images]);
    }

    public function modifyTypeValues($tableDescription)
    {

        foreach ($tableDescription as $key => &$column) {

            $type =  $column->Type;

            if (strpos($type, 'int') !== false) {
                $column->Type = 'INTEGER';
            }
            if (strpos($type, 'varchar') !== false) {
                $column->Type = 'TEXT';
            }

            if (strpos($type, 'date') !== false || strpos($type, 'timestamp') !== false) {
                $column->Type = 'NUMERIC';
            }
        }

        return $tableDescription;
    }

    public function getHazmat()
    {
        try {
            $hazmats = Hazmat::get();
            return response()->json(['isStatus' => true, 'message' => 'Hazmat list retrieved successfully.', 'hazmats' => $hazmats]);
        } catch (Throwable $th) {
            // print_r($th->getMessage());
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.']);
        }
    }
}

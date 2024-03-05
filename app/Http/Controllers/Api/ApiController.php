<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\shipOwners;
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

            // Check if user is already logged in from another device
            // $existingToken = PersonalAccessToken::where('tokenable_id', $user->id)->delete();
            // if ($existingToken) {
            //     return response()->json(['isStatus' => false, 'message' => 'You are already logged in from another device']);
            // }

            // Create a new token
            $token = $user->createToken('ApiToken')->plainTextToken;
            // $token = $user->createToken($user->name . '-AuthToken', ['expires' => now()->addMinutes(2)])->plainTextToken;
            $userData = $user->toArray();
            foreach ($userData as $key => $value) {
                $userData[$key] = $value ?? ''; // Replace null with empty string
            }

            return response()->json([
                'isStatus' => true,
                'message' => 'User login successful.',
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

    public function getshipOwnersList()
    {
        try {
            $owners = shipOwners::get(['id', 'name']);
            return $this->sendResponse($owners, 'Owner list retrieved successfully', 'ownerList');
        } catch (Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function getProjectList()
    {
        try {
            $project = Projects::with('ship_owner:id,name,image')->get();

            $modifiedProjects = [];

            if ($project->count() > 0) {
                $modifiedProjects = $project->map(function ($item) {
                    if (isset($item->ship_owner->image) && !empty($item->ship_owner->image)) {
                        $item->ship_owner->imagePath = url('public/images/ship/owner/') . '/' . $item->ship_owner->image;
                    }
                    return $item;
                });
            }

            return response()->json(['isStatus' => true, 'message' => 'Project list retrieved successfully.', 'projectList' => $modifiedProjects]);
        } catch (Throwable $th) {
            return response()->json(['isStatus' => false, 'message' => 'An error occurred while processing your request.', 'projectList' => []]);
        }
    }


    public function getShipDetail($project_id)
    {
        try {
            $project = Projects::with('ship_owner:name,address,id')->where('id', $project_id)->first()->toarray();
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
}

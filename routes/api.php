<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Route::post('login',[UserAuthController::class,'login']);
// Route::post('logout',[UserAuthController::class,'logout'])
//   ->middleware('auth:sanctum');


Route::controller(ApiController::class)->group(function () {
    // user Authentication api route
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('forgot_password', 'forgotPassword');
    Route::post('reset_password', 'resetPassword');

    // Routes that require Sanctum authentication
    Route::middleware(['auth:sanctum'])->group(function () {
        // User Api
        Route::get('refresh_token', 'refreshSanctumToken');
        Route::post('verify/code', 'verifyCode');
        Route::get('logout', 'logout');
        Route::post('change_password', 'changePassword');

        // project owner api route
        Route::get('ownerlist', 'getshipOwnersList');

        // project api route
        Route::get('projects', 'getProjectList');
        Route::get('project/{project_id}/surveyors/get', 'getProjectSurveyors');
        Route::post('project/surveyors/add', 'addProjectSurveyors');
        Route::get('project/shipDetials/{project_id}', 'getShipDetail');

        //project deck route
        Route::get('getDeckList/{project_id}', 'getDeckList');

        // check api route
        Route::get('checks/{deckId}','getCheckList');
        Route::get('check/details/{checkId}','getCheckDetail');
        Route::delete('check/{id}','deleteCheck');

        // Check image api
        Route::post('check/image', 'addCheckImg');
        Route::get('check/image/{check_id}', 'getCheckImgList');
        Route::delete('check/image/{id}', 'deleteCheckImg');
    });
});

<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\SyncProjectController;

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

Route::controller(SyncProjectController::class)->group(function () {
    Route::post('syncAdd', 'syncAdd');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('sync/project', 'syncProject');
        Route::post('zip/upload', 'zipUpload');
        Route::delete('remove/zip/{projectId}', 'removeZip');
        Route::post('create/zip', 'createZip');

    });
});

Route::controller(ApiController::class)->group(function () {
    // user Authentication api route
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('forgot_password', 'forgotPassword');
    Route::post('reset_password', 'resetPassword');

    Route::get('table/structure','tableStruture');


    // Routes that require Sanctum authentication
    Route::middleware(['auth:sanctum'])->group(function () {
        // User Api
        Route::get('refresh_token', 'refreshSanctumToken');
        Route::post('verify/code', 'verifyCode');
        Route::post('location', 'saveLocation');

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
        Route::post('check', 'editCheck');
        Route::post('check/add', 'addCheck');
        Route::post('qrCodePair','qrCodePair');

        Route::post('checks/list','getCheckList');
        Route::get('check/details/{checkId}','getCheckDetail');
        Route::delete('check/{id}','deleteCheck');
        Route::delete('pairWithTag/{id}','updatePairWithTag');

        // Check image api
        Route::post('check/image', 'addCheckImg');
        Route::get('check/image/{check_id}', 'getCheckImgList');
        Route::delete('check/image/{id}', 'deleteCheckImg');

        //Hazmat Api
        Route::get('getHazmat', 'getHazmat');
    });
});

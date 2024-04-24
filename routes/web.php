<?php

use App\Http\Controllers\ClientContoller;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//    return view('home');
// });

Route::get('/home', function () {
    return view('home');
})->name('home');
Route::get('otp',[UserController::class,'otp']);
Route::get('zoomLoad',[UserController::class,'zoomLoad']);
Route::post('verify/otp',[UserController::class,'verifyOtp']);

Route::get('/dashboard', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('can:roles')->group(function () {
        Route::controller(RoleController::class)->group(function () {
            Route::get('roles', 'index')->name('roles')->middleware('can:roles');
            Route::get('roles/add', 'create')->name('roles.add')->middleware('can:roles.add');
            Route::post('roles', 'store')->name('roles.store');
            Route::get('roles/{id}/edit', 'edit')->name('roles.edit')->middleware('can:roles.edit');
            Route::get('roles/{id}/delete', 'destroy')->name('roles.delete')->middleware('can:roles.remove');
        });
    });

    Route::middleware('can:permissions')->group(function () {
        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions');
    });

    Route::middleware('can:clients')->group(function () {
        Route::controller(ClientContoller::class)->group(function () {
            Route::get('clients', 'index')->name('clients')->middleware('can:clients');
            Route::get('clients/add', 'create')->name('clients.add')->middleware('can:clients.add');
            Route::post('clients', 'store')->name('clients.store');
            Route::get('clients/{id}/edit', 'edit')->name('clients.edit')->middleware('can:clients.edit');
            Route::get('clients/{id}/delete', 'destroy')->name('clients.delete')->middleware('can:clients.remove');
        });
    });

    Route::middleware('can:projects')->group(function () {
        Route::controller(ProjectsController::class)->group(function () {
            Route::get('projects', 'index')->name('projects')->middleware('can:projects');
            Route::get('projects/client/{client_id}', 'index')->name('projects.client');
            Route::get('project/add', 'create')->name('projects.add')->middleware('can:projects.add');
            Route::get('project/view/{project_id}', 'projectView')->name('projects.view');
            Route::post('project', 'store')->name('projects.store');
            Route::get('project/{id}/edit', 'edit')->name('projects.edit')->middleware('can:projects.edit');
            Route::get('project/{id}/delete', 'destroy')->name('projects.delete')->middleware('can:projects.remove');
            Route::post('detail/save', 'saveDetail')->name('projects.detail');
            Route::post('detail/assignProject', 'assignProject')->name('projects.assign');
            Route::post('addImageHotspots', 'addImageHotspots')->name('addImageHotspots');
            Route::post('project/save-image','saveImage');
            Route::post('project/updateDeckTitle','updateDeckTitle');
            Route::get('project/deleteDeckImg/{id}', 'deleteDeckImg')->name('deleteDeckImg');
            Route::get('projects/deck/{id}', 'deckBasedCheckView')->name('deck.detail');
            Route::post('/set-session', 'setBackSession')->name('set.session');

            Route::get('check/{id}/hazmat', 'checkBasedHazmat')->name('check.hazmat');
            Route::get('check/{id}/image', 'checkBasedImage')->name('check.image');

            Route::get('projects/check/{id}', 'deleteCheck')->name('check.delete');
        });
    });

    Route::middleware('can:users')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('users', 'index')->name('users')->middleware('can:users');
            Route::get('users/add', 'create')->name('users.add')->middleware('can:users.add');
            Route::post('users', 'store')->name('users.store');
            Route::get('users/{id}/edit', 'edit')->name('users.edit');
            Route::get('users/{id}/delete', 'destroy')->name('users.delete');
            Route::post('changeUserStatus', 'changeUserStatus')->name('change.isVerified');
        });
    });

    Route::get('generatorQRcode/{deckId}', [QrCodeController::class, 'show'])->name('generatorQRcode');

    Route::get('/viewQRCode', function () {
        return view('pdfView');
    })->name('viewQRCode');
});

require __DIR__ . '/auth.php';

Route::get('/migrate', function () {
    Artisan::call('migrate');
    return "Migrations Run Successfully";
});

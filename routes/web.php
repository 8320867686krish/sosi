<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipOwnersController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\shipContoller;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/dashboard', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('can:roles')->group(function () {
        Route::controller(RoleController::class)->group(function () {
            Route::get('roles', 'index')->name('roles');
            Route::get('roles/add', 'create')->name('roles.add');
            Route::post('roles', 'store')->name('roles.store');
            Route::get('roles/{id}/edit', 'edit')->name('roles.edit');
            Route::get('roles/{id}/delete', 'destroy')->name('roles.delete');
        });
    });

    Route::middleware('can:permissions')->group(function () {
        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions');
    });

    Route::middleware('can:ship_owners')->group(function () {
        Route::controller(ShipOwnersController::class)->group(function () {
            Route::get('shipOwners', 'index')->name('ship_owners');
            Route::get('shipOwners/add', 'create')->name('ship_owners.add');
            Route::post('shipOwners', 'store')->name('ship_owners.store');
            Route::get('shipOwners/{id}/edit', 'edit')->name('ship_owners.edit');
            Route::get('shipOwners/{id}/delete', 'destroy')->name('ship_owners.delete');
        });
    });

    Route::middleware('can:projects')->group(function () {
        Route::controller(ProjectsController::class)->group(function () {
            Route::get('projects', 'index')->name('projects');
            Route::get('projects/ships/{ship_owner_id}', 'index')->name('projects.ship');
            Route::get('project/add', 'create')->name('projects.add');
            Route::get('project/view/{project_id}', 'projectView')->name('projects.view');
            Route::post('project', 'store')->name('projects.store');
            Route::get('project/{id}/edit', 'edit')->name('projects.edit');
            Route::get('project/{id}/delete', 'destroy')->name('projects.delete');
            Route::post('detail/save', 'saveDetail')->name('projects.detail');
            Route::post('detail/assignProject', 'assignProject')->name('projects.assign');
        });
    });

    Route::middleware('can:users')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('users', 'index')->name('users');
            Route::get('users/add', 'create')->name('users.add');
            Route::post('users', 'store')->name('users.store');
            Route::get('users/{id}/edit', 'edit')->name('users.edit');
            Route::get('users/{id}/delete', 'destroy')->name('users.delete');
            Route::post('changeUserStatus', 'changeUserStatus')->name('change.isVerified');
        });
    });
});

require __DIR__ . '/auth.php';

Route::get('/migrate', function () {
    Artisan::call('migrate');
    return "Migrations Run Successfully";
});

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return redirect('app');
});

Auth::routes();
Route::middleware('auth')->group(function () {
	Route::prefix('/services')->group(function () {
		Route::prefix('profile')->group(function () {
			Route::get('/', function () {
				$user = App\User::with(['roles.permissions','permissions'])->find(request()->user()->id);
				return $user;
			});
			Route::post('/', 'UserController@updateProfile');
			Route::post('/update/image', 'UserController@updateProfilePicture');
		});

		Route::prefix('/management')->group(function () {
			Route::apiResource('/user', 'UserController');
			Route::apiResource('/permission', 'PermissionController');
			Route::apiResource('/role', 'RoleController');
			Route::put('/menu/update', 'MenuController@updateSort');
			Route::apiResource('/menu', 'MenuController', ['except'=> ['show']]);
		});
	});

	Route::prefix('/app')->group(function () {
		Route::get('/{vue_capture?}', function () {
			$user = App\User::with(['roles.permissions','permissions'])->find(request()->user()->id);
			return view('layouts.app', compact('user'));
		})->where('vue_capture', '[\/\w\.-]*');
	});
});


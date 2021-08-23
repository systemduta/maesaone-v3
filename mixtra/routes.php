<?php
use Illuminate\Support\Facades\Route;

$namespace = '\Mixtra\Controllers';

// Route::get('/admin', function () {
//     return redirect(config('mixtra.admin_path'));
// });

Route::group([
    'middleware' => ['web'],
    'prefix' => config('mixtra.admin_path'),
    'namespace' => $namespace,
], function () {
    Route::get('login', ['uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login']);
    Route::post('login', ['uses' => 'Auth\LoginController@login']);
    Route::post('logout', ['uses' => 'Auth\LoginController@logout', 'as' => 'logout']);
});

Route::group([
    'middleware' => ['web', 'auth'],
    'prefix' => config('mixtra.admin_path'),
    'namespace' => $namespace,
], function () {
    try {
        $menus = DB::table('mit_menus')
            ->where('is_default', true)
            ->whereRaw("controller is not null and controller != ''")
            ->get();

        foreach ($menus as $item) {
            MITBooster::routeController($item->slug, $item->controller, '\Mixtra\Controllers');
        }
    } catch (Exception $e) {
    }
});

Route::group([
    'middleware' => ['web', 'auth'],
    'prefix' => config('mixtra.admin_path'),
    'namespace' => 'App\Http\Controllers',
], function () {
    MITBooster::routeController('/', 'HomeController', '\Mixtra\Controllers');
    try {
        $menus = DB::table('mit_menus')
            ->where('is_default', false)
            ->whereRaw("controller is not null and controller != ''")
            ->get();

        foreach ($menus as $item) {
            MITBooster::routeController($item->slug, $item->controller);
        }
    } catch (Exception $e) {
    }
});

/* ROUTER FOR UPLOADS */
Route::group(['middleware' => ['web'], 'namespace' => $namespace], function () {
    Route::get('uploads/{one?}/{two?}/{three?}/{four?}/{five?}', ['uses' => 'FileController@getPreview', 'as' => 'fileControllerPreview']);
});

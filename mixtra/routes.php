<?php
use Illuminate\Support\Facades\Route;

$namespace = '\Mixtra\Controllers';
$domain = config('mixtra.subdomain');

Route::group([
    'middleware' => ['web'],
    'prefix' => config('mixtra.admin_path'),
    'namespace' => $namespace,
    'domain' => $domain,
], function () {
    Route::get('login', ['uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login']);
    Route::post('login', ['uses' => 'Auth\LoginController@login']);
    Route::post('logout', ['uses' => 'Auth\LoginController@logout', 'as' => 'logout']);
});

Route::group([
    'middleware' => ['web', 'auth'],
    'prefix' => config('mixtra.admin_path'),
    'namespace' => $namespace,
    'domain' => $domain,
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
    'domain' => $domain,
], function () {
    MITBooster::routeController('/', 'AdminController', '\Mixtra\Controllers');
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

/* ROUTER FOR API */
Route::group([
    'middleware' => ['Mixtra\Middleware\ApiAgent'],
    'prefix' => config('mixtra.api_path'),
    'namespace' => 'App\Http\Api',
], function () {
    Route::post('login', ['uses' => 'UserApiController@login', 'as' => 'UserApiControllerLogin']);
});

/* ROUTER FOR API */
Route::group([
    'middleware' => ['Mixtra\Middleware\ApiAgent','Mixtra\Middleware\ApiAuth'],
    'prefix' => config('mixtra.api_path'),
    'namespace' => 'App\Http\Api',
], function () use ($namespace) {
    $dir = scandir(base_path("app/Http/Api"));
    foreach ($dir as $v) {
        if ($v == "." || $v == "..") {
            continue;
        }
        $controller = str_replace('.php', '', $v);
        $names = array_filter(preg_split('/(?=[A-Z])/', str_replace('ApiController', '', $controller)));
        $names = strtolower(implode('_', $names));

        MITBooster::routeController($names, $controller, 'App\Http\Api');
    }
});

<?php

namespace Mixtra\Helpers;

use Auth;
use Cache;
use DB;
use Route;
use Request;
use Session;
use Storage;
use Image;
use Illuminate\Support\Str;

class MITBooster
{
    public static function insertLog($description, $details = '')
    {
        $a = [];
        $a['created_at'] = date('Y-m-d H:i:s');
        $a['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $a['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $a['url'] = Request::url();
        $a['description'] = $description;
        $a['details'] = $details;
        $a['user_id'] = Auth::user()->id;
        DB::table('mit_logs')->insert($a);
    }

    public static function routeController($prefix, $controller, $namespace = null)
    {
        $prefix = trim($prefix, '/').'/';

        $namespace = ($namespace) ?: 'App\Http\Controllers';

        try {
            Route::get($prefix, ['uses' => $controller.'@getIndex', 'as' => $controller.'GetIndex']);
            $controller_class = new \ReflectionClass($namespace.'\\'.$controller);
            $controller_methods = $controller_class->getMethods(\ReflectionMethod::IS_PUBLIC);
            $wildcards = '/{one?}/{two?}/{three?}/{four?}/{five?}';
            foreach ($controller_methods as $method) {
                if ($method->class != 'Illuminate\Routing\Controller' && $method->name != 'getIndex') {
                    if (substr($method->name, 0, 3) == 'get') {
                        $method_name = substr($method->name, 3);
                        $slug = array_filter(preg_split('/(?=[A-Z])/', $method_name));
                        $slug = strtolower(implode('-', $slug));
                        $slug = ($slug == 'index') ? '' : $slug;
                        Route::get($prefix.$slug.$wildcards, ['uses' => $controller.'@'.$method->name, 'as' => $controller.'Get'.$method_name]);
                    } elseif (substr($method->name, 0, 4) == 'post') {
                        $method_name = substr($method->name, 4);
                        $slug = array_filter(preg_split('/(?=[A-Z])/', $method_name));
                        Route::post($prefix.strtolower(implode('-', $slug)).$wildcards, [
                            'uses' => $controller.'@'.$method->name,
                            'as' => $controller.'Post'.$method_name,
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }

    public static function getSetting($name)
    {
        $query = DB::table('mit_settings')->where('name', $name)->first();
        return $query->content;
    }

    public static function myId()
    {
        return Auth::user()->id;
    }

    public static function myName()
    {
        return Auth::user()->name;
    }

    public static function myPhoto()
    {
        if (Auth::user()->photo) {
            return Auth::user()->photo;
        } else {
            return self::gravatar(Auth::user()->email);
        }
    }

    public static function gravatar( $email, $s = 80, $d = 'mp', $r = 'g', $img = false, $atts = array() ) {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    public static function getCurrentModule()
    {
        $modulepath = self::getModulePath();
        if (Cache::has('modules_'.$modulepath)) {
            return Cache::get('modules_'.$modulepath);
        } else {
            $module = DB::table('mit_menus')->where('slug', self::getModulePath())->first();
            return $module;
        }
    }

    private static function getModulePath()
    {
        $adminPathSegments = count(explode('/', config('mixtra.admin_path')));
        return Request::segment(1 + $adminPathSegments);
    }

    public static function getCurrentMethod()
    {
        $action = str_replace("App\Http\Controllers", "", Route::currentRouteAction());
        $atloc = strpos($action, '@') + 1;
        $method = substr($action, $atloc);

        return $method;
    }

    public static function isSuperadmin()
    {
        $role = db::table('mit_roles')->find(Auth::user()->mit_role_id);
        return $role->is_superadmin;
    }

    public static function isView()
    {
        if (self::isSuperadmin()) {
            return true;
        }

        $role = db::table('mit_roles_menus as a')
            ->join('mit_menus as b', 'a.mit_menu_id', 'b.id')
            ->where('mit_role_id', Auth::user()->mit_role_id)
            ->where('slug', self::getModulePath())
            ->select("a.*")
            ->first();
        return (bool) $role->is_visible;
    }

    public static function isUpdate()
    {
        if (self::isSuperadmin()) {
            return true;
        }

        $role = db::table('mit_roles_menus as a')
            ->join('mit_menus as b', 'a.mit_menu_id', 'b.id')
            ->where('mit_role_id', Auth::user()->mit_role_id)
            ->where('slug', self::getModulePath())
            ->select("a.*")
            ->first();
        return (bool) $role->is_edit;
    }

    public static function isCreate()
    {
        if (self::isSuperadmin()) {
            return true;
        }

        $role = db::table('mit_roles_menus as a')
            ->join('mit_menus as b', 'a.mit_menu_id', 'b.id')
            ->where('mit_role_id', Auth::user()->mit_role_id)
            ->where('slug', self::getModulePath())
            ->select("a.*")
            ->first();
        return (bool) $role->is_create;
    }

    public static function isRead()
    {
        if (self::isSuperadmin()) {
            return true;
        }

        $role = db::table('mit_roles_menus as a')
            ->join('mit_menus as b', 'a.mit_menu_id', 'b.id')
            ->where('mit_role_id', Auth::user()->mit_role_id)
            ->where('slug', self::getModulePath())
            ->select("a.*")
            ->first();
        return (bool) $role->is_read;
    }

    public static function isDelete()
    {
        if (self::isSuperadmin()) {
            return true;
        }

        $role = db::table('mit_roles_menus as a')
            ->join('mit_menus as b', 'a.mit_menu_id', 'b.id')
            ->where('mit_role_id', Auth::user()->mit_role_id)
            ->where('slug', self::getModulePath())
            ->select("a.*")
            ->first();
        return (bool) $role->is_delete;
    }

    public static function mainpath($path = null)
    {
        $controllername = str_replace(["\Mixtra\Controllers\\", "App\Http\Controllers\\"], "", strtok(Route::currentRouteAction(), '@'));
        $route_url = route($controllername.'GetIndex');

        if ($path) {
            if (substr($path, 0, 1) == '?') {
                return trim($route_url, '/').$path;
            } else {
                return $route_url.'/'.$path;
            }
        } else {
            return trim($route_url, '/');
        }
    }

    public static function adminPath($path = null)
    {
        return url(config('mixtra.admin_path').'/'.$path);
    }

    public static function urlFullText($key, $type = '', $value = '')
    {
        $params = Request::all();
        $mainpath = trim(self::mainpath(), '/');

        foreach ($params as $k => $param) {
            if ($key == "q" || $key == "limit" || $key == "format") {
                if ($k == $key) {
                    unset($params[$k]);
                }
            } else {
                $len = strlen($type)+1;
                if (substr($k, 0, $len) == $type."_") {
                    unset($params[$k]);
                }
            }

            if (substr($k, 0, 4) == 'amp;') {
                $value = $param;
                $new_key = str_replace('amp;', '', $k);
                $params[$new_key] = $value;
                unset($params[$key]);
            }
        }
        
        if ($key != "q" && $key != "limit" && $key != "format") {
            $params[$type.'_'.$key] = $value;
        }
        
        
        if (isset($params)) {
            return $mainpath.'?'.urldecode(http_build_query($params));
        }

        return $mainpath;
    }

    // public static function redirectBack($message, $type = 'warning')
    // {
    //     if (Request::ajax()) {
    //         $resp = response()->json(['message' => $message, 'message_type' => $type, 'redirect_url' => $_SERVER['HTTP_REFERER']])->send();
    //         exit;
    //     } else {
    //         $resp = redirect()->back()->with(['message' => $message, 'message_type' => $type]);
    //         session(['message' => $message, 'message_type' => $type]);
    //         Session::driver()->save();
    //         $resp->send();
    //         exit;
    //     }
    // }

    public static function redirect($to, $message, $type = 'warning')
    {
        if (Request::ajax()) {
            return response()->json(['message' => $message, 'message_type' => $type, 'redirect_url' => $to]);
        } else {
            Session::put('message', $message);
            Session::put('message_type', $type);
            return redirect($to);
        }
    }

    public static function referer()
    {
        return Request::server('HTTP_REFERER');
    }

    public static function uploadFile($name, $encrypt = true, $resize_width = null, $resize_height = null)
    {
        if (Request::hasFile($name)) {
            $file = Request::file($name);
            $ext = $file->getClientOriginalExtension();
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filesize = $file->getSize() / 1024;
            $file_path = 'uploads/'.date('Y-m');

            //Create Directory Monthly
            Storage::makeDirectory($file_path);

            if ($encrypt == true) {
                $filename = md5(Str::random(5)).'.'.$ext;
            } else {
                $filename = Str::slug($filename, '_').'.'.$ext;
            }

            if (Storage::putFileAs($file_path, $file, $filename)) {
                self::resizeImage($file_path.'/'.$filename, $resize_width, $resize_height);

                return $file_path.'/'.$filename;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    private static function resizeImage($fullFilePath, $ext, $resize_width = null, $resize_height = null, $qty = 100, $thumbQty = 75)
    {
        $images_ext = config('mixtra.IMAGE_EXTENSIONS', 'jpg,png,gif,bmp');
        $images_ext = explode(',', $images_ext);

        $filename = basename($fullFilePath);
        $file_path = trim(str_replace($filename, '', $fullFilePath), '/');

        // $file = Request::file($name);
        // $ext = $file->getClientOriginalExtension();
        $ext =pathinfo($filename, PATHINFO_EXTENSION);

        $file_path_thumbnail = 'uploads_thumbnail/'.date('Y-m');
        Storage::makeDirectory($file_path_thumbnail);

        if (in_array(strtolower($ext), $images_ext)) {
            if ($resize_width && $resize_height) {
                $img = Image::make(storage_path('app/'.$file_path.'/'.$filename));
                $img->fit($resize_width, $resize_height);
                $img->save(storage_path('app/'.$file_path.'/'.$filename), $qty);
            } elseif ($resize_width && ! $resize_height) {
                $img = Image::make(storage_path('app/'.$file_path.'/'.$filename));
                $img->resize($resize_width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(storage_path('app/'.$file_path.'/'.$filename), $qty);
            } elseif (! $resize_width && $resize_height) {
                $img = Image::make(storage_path('app/'.$file_path.'/'.$filename));
                $img->resize(null, $resize_height, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save(storage_path('app/'.$file_path.'/'.$filename), $qty);
            } else {
                $img = Image::make(storage_path('app/'.$file_path.'/'.$filename));
                if ($img->width() > 1300) {
                    $img->resize(1300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                $img->save(storage_path('app/'.$file_path.'/'.$filename), $qty);
            }

            $img = Image::make(storage_path('app/'.$file_path.'/'.$filename));
            $img->fit(350, 350);
            $img->save(storage_path('app/'.$file_path_thumbnail.'/'.$filename), $thumbQty);
        }
    }
}
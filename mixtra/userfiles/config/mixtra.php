<?php

return [
    'subdomain' => env('SUBDOMAIN', ''),
    'admin_path' => env('ADMIN_PATH', 'admin'),
    'image_extensions' => 'jpg jpeg png',
    'default_thumbnail_width' => 0,
    'default_upload_max_size' => '1M',
    'password_fields_candidate' => 'password,pass,pwd,passwrd,sandi,pin',
    
    'api_path' => env('API_PATH', 'api'),
    'api_secret' => env('JWT_SECRET'),
    'agent_allowed' => [],
];

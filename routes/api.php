<?php

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('/users', 'UserController');
    Route::resource('/groups', 'GroupController');
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Not Found',
        'documentation_url' => env('DOCUMENTATION_URL'),
    ], 404);
});
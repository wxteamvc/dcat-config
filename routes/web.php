<?php

use Dcat\Admin\Extension\Config\Http\Controllers;

Route::get('setConfig', Controllers\ConfigController::class.'@index');

Route::resource('addConfigs', Controllers\AddConfigController::class);
Route::resource('addGroups', Controllers\AddGroupController::class);
Route::any('/app_file/upload', Controllers\FileController::class. "@handle" );

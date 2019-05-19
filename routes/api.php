<?php

use Illuminate\Http\Request;

Route::group(array('prefix' => 'v1', 'middleware' => []), function () {

    Route::group(array('prefix' => 'authen', 'middleware' => []), function () {
        Route::post('login', 'UserController@getUserByToken');
        Route::get('test', 'UserController@getUserFromToken');
        Route::get('logout', 'UserController@logout');
    });

    Route::group(array('prefix' => 'admin', 'middleware' => ['scopes:admin']), function () {
        Route::resource('user', 'UserController', ['only' => [
            'index'
        ]]);

        Route::get('level/{id}/max-scores', 'LevelController@getMaxScores');
        Route::post('level/{id}/max-scores', 'LevelController@storeMaxScores');

        Route::resource('level', 'LevelController', ['except' => [
            'create', 'edit'
        ]]);

        Route::resource('question', 'QuestionController', ['except' => [
            'create', 'edit'
        ]]);
        Route::resource('part', 'PartController', ['except' => [
            'create', 'edit'
        ]]);
        Route::resource('kid', 'KidController', ['except' => [
            'create', 'edit'
        ]]);
        Route::resource('test-result', 'TestResultController', ['except' => [
            'create', 'edit'
        ]]);
        Route::resource('test-evaluate', 'TestEvaluateController', ['except' => [
            'create', 'edit'
        ]]);
        Route::resource('answer-sheet', 'AnswerSheetController', ['except' => [
            'create', 'edit'
        ]]);
        Route::resource('chart-data', 'ChartDataController', ['except' => [
            'create', 'edit'
        ]]);
        Route::get('find-user', 'UserController@findUser');
        Route::get('get-test-results', 'TestResultController@getTestResult');
    });

    Route::group(array('prefix' => 'client'), function () {
        Route::get('login', 'Client\UserController@login');

        Route::group(['middleware' => ['scope:client,admin']], function () {
            Route::get('get-user-info', 'UserController@getUserInfoFromToken');
            Route::get('test', 'Client\TestController@index');
            Route::post('test', 'Client\TestController@store');
            Route::get('parts', 'Client\PartController@index');
            Route::get('get-babies-profile', 'Client\UserController@getTestResult');
            Route::get('uddate-selected-kid', 'Client\UserController@getTestResult');

            Route::post('kids', 'Client\KidController@store');
            Route::put('kids/{id}', 'Client\KidController@update');
            Route::delete('kids/{id}', 'Client\KidController@destroy');

            Route::get('users/me', 'Client\UserController@show');
            Route::put('users/me', 'Client\UserController@update');
        });
    });

    Route::post('images-upload', 'UploadController@fileUpload');

});

<?php
Route::get('/', function(){
   return redirect()->route('surveys.index');
});

Route::get('/surveys/data', 'SurveysController@data')->name('surveys.data');
Route::get('/users', 'UsersController@getData')->name('users.data');
Route::get('/surveys/{survey}/users', 'SurveysController@users')->name('surveys.users');
Route::resource('/surveys', 'SurveysController');
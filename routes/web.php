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

Auth::routes();

Route::get('/', 'CondoController@index')->name('home');

Route::get('/gauth', 'GoogleController@gauth');

/* Hue Lights and Wemo Switches */
Route::post('/lights',  'CondoController@lightswitch');
Route::post('/outlets', 'CondoController@outletswitch');

/* Turn off Lights Randomly at night, simulating
   someone being home. */
Route::get('/night',  'NightController@index');
Route::post('/night', 'NightController@store');

/* Sliders on Setup Page. */
Route::get('/options',  'SliderController@index');
Route::post('/options', 'SliderController@store');

/* Display Setup pages */
Route::get('/setup',          'SetupController@index');
Route::get('/setup/reset',    'SetupController@reset');
Route::get('/setup/timers',   'TimerController@index');
Route::get('/setup/buttons',  'ButtonController@index');
Route::post('/setup/buttons', 'ButtonController@store');
Route::post('/setup/timers',  'TimerController@store');

/* Turn lights on when cloudy and low natural light. */
Route::get('/cloudy',  'CloudyController@index');
Route::post('/cloudy', 'CloudyController@store');

/* Door, Doorbell, Power, Boundry, Alarm, and other statuses */
Route::get('/status/',          'StatusController@index');
Route::get('/status/door',      'StatusController@door');
Route::get('/status/doorbell',  'StatusController@doorbell');
Route::get('/status/power',     'StatusController@power');
Route::get('/status/activity',  'StatusController@activity');
Route::get('/status/sensors',   'StatusController@sensors');

/* Set Home/Away, Active/Inactive, and setup sensors. */
Route::get('/sensors/options',  'SensorOptionsController@index');
Route::post('/sensors/options', 'SensorOptionsController@store');
Route::get('/sensors/setup',    'SensorSetupController@index');
Route::post('/sensors/setup',   'SensorSetupController@store');

/* Get an API Key for a user. */
Route::get('/home/apikey', 'HomeController@apikey');

/* Opt In / Out Routes for SMS Messaging. */
Route::get('/optin', 'HomeController@optin');
Route::get('/optout', 'HomeController@optout');
Route::post('/optin', 'HomeController@saveoptin')->name('optin.save');
Route::post('/optout', 'HomeController@saveoptout')->name('optout.save');;

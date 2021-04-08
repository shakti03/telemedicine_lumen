<?php


/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('test-admin', function () {
    $user = App\Models\User::whereEmail('admin@telemedicine.com')->first();
    $user->email_verified_at = date('Y-m-d H:i:S');
    $user->save();
});

$router->get('send-mail', 'CommonController@sendEmail');
$router->get('create-meeting', 'TestGotoMeeting@createMeeting');
$router->get('create-order', 'PayPalController@createOrder');
$router->get('paypal/return', 'PayPalController@onApprove');
$router->get('paypal/cancel', 'PayPalController@cancelOrder');
$router->post('paypal/notify', 'PayPalController@notifyPayment');

$router->get('verify-email', 'AuthController@verifyUser');

$router->group(['prefix' => "api"], function () use ($router) {
    $router->post('login', 'AuthController@authenticate');
    $router->post('register', 'AuthController@register');
    $router->get('symptoms', 'CommonController@getSymptoms');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['prefix' => 'physician', 'namespace' => 'Physician'], function () use ($router) {
            // Profile
            $router->get('profile', 'ProfileController@getProfile');
            $router->put('profile', 'ProfileController@updateProfile');
            $router->put('password', 'ProfileController@updatePassword');

            // Appointment Detail
            $router->get('appointment-detail', 'AppointmentController@getAppointmentSetting');
            $router->put('appointment-detail', 'AppointmentController@updateAppointmentSetting');
            $router->put('appointment-detail/schedules', 'AppointmentController@updateSchedules');
            $router->put('appointment-detail/questions', 'AppointmentController@updateQuestions');

            $router->get('appointments', 'AppointmentController@getAppointments');
            $router->post('appointments/{appointmentId}/status', 'AppointmentController@changeAppointmentStatus');
        });
    });

    // $router->group(['prefix' => 'public'], function () use ($router) {
    $router->get('physician/{physicianLink}/meeting-detail', 'AppointmentController@getPhysicianMeetingDetail');
    $router->post('appointments', 'AppointmentController@store');

    // });
});

$router->get('/', function () {
    return view('index');
});

// account|dashboard|appointment-manager
$router->get('/{route:.*}/', function () {
    return view('index');
}); 

// Route::group([ 'prefix' => 'api/'], function (){
//     Route::group(['middleware' => 'auth:api','cors'], function() {

//         Route::get('physicianLink', 'DashboardController@physicianLink');

//         Route::get('checkRoom', 'AppointmentController@checkRoom');

//         Route::get('meetingTitle', 'AppointmentController@meetingTitle');
//         Route::get('eventName', 'AppointmentController@eventName');
//         Route::get('getEventDetails', 'AppointmentController@getEventDetails');
//         Route::post('updateEventDetails', 'AppointmentController@updateEventDetails');

//         Route::get('getQuestion', 'AppointmentController@getQuestion');
//         Route::post('createQuestion', 'AppointmentController@createQuestion');
//         Route::post('editQuestion', 'AppointmentController@editQuestion');
//         Route::post('deleteQuestion', 'AppointmentController@deleteQuestion');

//         Route::get('seletedAppointmentSlot', 'AppointmentController@seletedAppointmentSlot');
//         Route::get('getAppointmentSlot', 'AppointmentController@getAppointmentSlot');
//         Route::post('bookAppointmentSlot', 'AppointmentController@bookAppointmentSlot');

//     });
// });

// Route::get('test', 'AppointmentController@test');

// // Route::get('test', function (Request $request) {
// // });


// // Route::group(['prefix' => 'api/','middleware' => 'auth:api'], function(){

// Route::group(array('prefix' => 'api/'), function() {

//     Route::post('AddScheduleEvent', 'ScheduleEventController@add');
//     Route::get('pendingAppointment', 'ScheduleEventController@pendingAppointment');
//     Route::get('upcomingAppointment', 'ScheduleEventController@upcomingAppointment');
//     Route::get('pastAppointment', 'ScheduleEventController@pastAppointment');
//     Route::post('updateConfirmation', 'ScheduleEventController@updateConfirmation');
//     Route::get('symptomsSearch', 'AppointmentController@symptomsSearch');

//     Route::get('physicianData/{url}', 'BookingController@physicianData');
//     Route::get('physicianQuestions/{url}', 'BookingController@physicianQuestions');


//     Route::get('searchAutoComplete', 'SearchController@searchAutoComplete');
//     Route::get('getTimezone', 'AppointmentController@getTimezone');
//     Route::get('getUserdata', 'AppointmentController@getUserdata');
//     Route::post('updateUserdata', 'AppointmentController@updateUserdata');


//     Route::get('search', 'SearchController@Listing');


//     Route::post('login', 'AuthController@login');
//     Route::post('signup', 'AuthController@signup');

// });

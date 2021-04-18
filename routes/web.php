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
$router->get('paypal/return', 'PayPalController@onApprove');
$router->get('paypal/cancel', 'PayPalController@cancelOrder');
$router->post('paypal/notify', 'PayPalController@notifyPayment');

$router->get('verify-email', 'AuthController@verifyUser');

$router->group(['prefix' => "api"], function () use ($router) {
    $router->post('login', 'AuthController@authenticate');
    $router->post('register', 'AuthController@register');
    $router->post('send-reset-link', 'AuthController@generateResetToken');
    $router->post('reset-password', 'AuthController@resetPassword');

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
            $router->get('waiting-appointments', 'AppointmentController@waitingAppointments');
            $router->get('appointment-stats', 'AppointmentController@getAppointmentStats');
            $router->get('earnings', 'AppointmentController@getTotalEarnings');
            $router->post('appointments/{appointmentId}/status', 'AppointmentController@changeAppointmentStatus');
        });
    });

    $router->get('physician/{physicianLink}/meeting-detail', 'AppointmentController@getPhysicianMeetingDetail');
    $router->post('appointments', 'AppointmentController@store');
});

$router->get('auth/password-reset', ['as' => 'password.reset', 'uses' => 'Controller@home']);

$router->get('/', 'Controller@home');

$router->get('/{route:.*}/', 'Controller@home'); // account|dashboard|appointment-manager
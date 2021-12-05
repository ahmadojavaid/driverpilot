<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::post('/register', 'AuthController@register');

Route::post('/login', 'AuthController@login');
Route::post('/logout', 'AuthController@logout');
Route::get('/user/confirm/{id}/{token}', 'AuthController@checkConfirmation');
Route::post('me', 'AuthController@user');
Route::get('school', 'SchoolController@getSchools');
Route::post('recovery-password', 'AuthController@recorverPassword');

Route::group(['prefix' => 'student', 'middleware' => ['jwt.verify', 'auth.student']], function() {
    Route::get('profile/{id}', 'Student\StudentController@getProfile');
    Route::get('instructor/find', 'Student\StudentController@getInstructors');
    Route::get('instructor/{id}/view', 'Student\StudentController@viewInstructor');
    Route::post('profile/{id}/update', 'Student\StudentController@updateProfile');
    Route::post('result/{modid}/{userid}', 'Student\QuestionController@calculateTestResult');
    Route::get('activity/{userid}', 'Student\ActivityController@getActivity');
    Route::post('rating/{ins_id}', 'Student\StudentController@saveRating');
});

Route::group(['prefix' => 'admin', 'middleware' => ['jwt.verify', 'auth.admin']], function() {
    Route::post('create/school', 'AuthController@register');
    Route::post('create/{id}/question', 'Admin\QuestionController@createQuestions');
    Route::get('school', 'Admin\SchoolController@getSchools');
    Route::post('create/module', 'Admin\DrivingController@createModule');
});

Route::group(['prefix' => 'instructor', 'middleware' => ['jwt.verify', 'auth.instructor']], function() {
    Route::post('create/topic', 'Instructor\InstructorController@createTopic');
    Route::get('school', 'Admin\SchoolController@getSchools');
});










<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'indx', 'uses' => 'WelcomeController@index']);
Route::get('/locale/{lang}', 'WelcomeController@changeLocale');

Route::post('/admin/password', '\App\Http\Controllers\Admin\AdminController@changePassword');

Route::get('/admin/sitemap', '\App\Http\Controllers\Admin\AdminController@sitemapGen');

Route::get('admin/settings/all', '\App\Http\Controllers\Admin\AdminSettingController@all');
Route::resource('admin/settings', '\App\Http\Controllers\Admin\AdminSettingController');
Route::get('admin/settings/del/{id}', '\App\Http\Controllers\Admin\AdminSettingController@del');

Route::get('admin/queues/all', '\App\Http\Controllers\Admin\AdminQueueController@all');
Route::resource('admin/queues', '\App\Http\Controllers\Admin\AdminQueueController');
Route::get('admin/queues/del/{id}', '\App\Http\Controllers\Admin\AdminQueueController@del');

Route::get('admin/questions/all', 'Admin\AdminQuestionController@all');
Route::post('admin/questions/change_visible_many', 'Admin\AdminQuestionController@changeVisibleMany');
Route::match(array('GET', 'POST'),'admin/questions/filter', ['as' => 'questionFilter', 'uses' => 'Admin\AdminQuestionController@filter']);
Route::resource('admin/questions', 'Admin\AdminQuestionController');
Route::get('admin/questions/del/{id}', 'Admin\AdminQuestionController@del');
Route::post('admin/questions/del_many', 'Admin\AdminQuestionController@delMany');

Route::get('admin/answers/all', 'Admin\AdminAnswerController@all');
Route::post('admin/answers/change_visible_many', 'Admin\AdminAnswerController@changeVisibleMany');
Route::match(array('GET', 'POST'),'admin/answers/filter', ['as' => 'answerFilter', 'uses' => 'Admin\AdminAnswerController@filter']);
Route::resource('admin/answers', 'Admin\AdminAnswerController');
Route::get('admin/answers/del/{id}', 'Admin\AdminAnswerController@del');
Route::post('admin/answers/del_many', 'Admin\AdminAnswerController@delMany');

Route::get('admin/members/all', 'Admin\AdminMemberController@all');
Route::post('admin/members/change_visible_many', 'Admin\AdminMemberController@changeVisibleMany');
Route::match(array('GET', 'POST'),'admin/members/filter', ['as' => 'memberFilter', 'uses' => 'Admin\AdminMemberController@filter']);
Route::resource('admin/members', 'Admin\AdminMemberController');
Route::get('admin/members/del/{id}', 'Admin\AdminMemberController@del');
Route::post('admin/members/del_many', 'Admin\AdminMemberController@delMany');

Route::get('admin/ratings/all', 'Admin\AdminRatingController@all');
Route::post('admin/ratings/change_visible_many', 'Admin\AdminRatingController@changeVisibleMany');
Route::match(array('GET', 'POST'),'admin/ratings/filter', ['as' => 'ratingFilter', 'uses' => 'Admin\AdminRatingController@filter']);
Route::resource('admin/ratings', 'Admin\AdminRatingController');
Route::get('admin/ratings/del/{id}', 'Admin\AdminRatingController@del');
Route::post('admin/ratings/del_many', 'Admin\AdminRatingController@delMany');

Route::post('answers/more', 'AnswerController@more');

Route::post('rating', 'RatingController@ratingChange');

Route::get('/question/{questionSlug}', 'QuestionController@oneQuestion');
Route::post('/question-publish', 'QuestionController@publish');

Route::get('/answer/{answerSlug}', 'AnswerController@oneAnswer');
Route::post('/answer-publish', 'AnswerController@publish');

/***************API***************************/
Route::get('/api/start/{page}', ['as' => 'api-indx', 'uses' => 'WelcomeController@indexApi']);
Route::get('/api/answers/{id}/{page}', ['as' => 'api-answ', 'uses' => 'AnswerController@showForApi']);
Route::get('/api/new', ['as' => 'api-new', 'uses' => 'WelcomeController@checkNewsApi']);




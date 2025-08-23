<?php

use App\Http\Controllers\Api\EducationPageController;
use App\Http\Controllers\Api\HelpcenterPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\React\CMS\HomeController;
use App\Http\Controllers\Api\React\Chat\ChatController;
use App\Http\Controllers\Api\React\DashboardController;
use App\Http\Controllers\Api\React\Calendar\CalendarController;
use App\Http\Controllers\Api\React\User\Auth\SocialLoginController;
use App\Http\Controllers\Api\React\User\Auth\ResetPasswordController;
use App\Http\Controllers\Api\React\User\Auth\AuthenticationController;
use App\Http\Controllers\Api\React\Notification\NotificationController;
use App\Http\Controllers\Web\Backend\EducationController;

//health-check
Route::get("/check", function () {
    return "Project is running!";
});

//Guest user routes
Route::group(['middleware' => 'guest:api'], function () {
    //help center page routes
    Route::get('/faq', [HelpcenterPageController::class, 'getFaqs']);

    //help center hero section route
    Route::get('/hero', [HelpcenterPageController::class, 'helpCenterHero']);

    //eudcation routes
    Route::get('/education/list', [EducationPageController::class, 'getEducationlist']);
    Route::get('/education/{id}', [EducationPageController::class, 'show']);

    //pinned education
    Route::get('/pinned/education', [EducationPageController::class,'pinnedEducation']);
});




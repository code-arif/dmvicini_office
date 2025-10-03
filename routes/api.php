<?php

use App\Http\Controllers\Api\EducationPageController;
use App\Http\Controllers\Api\HelpcenterPageController;
use Illuminate\Support\Facades\Route;

//health-check
Route::get("/check", function () {
    return "All right!... 👍";
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




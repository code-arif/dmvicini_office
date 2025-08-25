<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\EducationController;
use App\Http\Controllers\Web\Backend\CMS\AuthPageController;
use App\Http\Controllers\Web\Backend\CMS\HelpCenterController;
use App\Http\Controllers\Web\Backend\Investment\AssetClassController;
use App\Http\Controllers\Web\Backend\Investment\InvestmentController;
use App\Http\Controllers\Web\Backend\Investment\InvestmentStrategyController;
use App\Http\Controllers\Web\Backend\Investment\InvestmentTypeController;
use App\Http\Controllers\Web\Backend\Settings\ProfileController;
use App\Http\Controllers\Web\Backend\Settings\SettingController;
use App\Http\Controllers\Web\Backend\Settings\DynamicPageController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard data for charts
    Route::get('dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');

    // cms management
    Route::prefix('cms')->name('cms.')->group(function () {
        //help center page
        Route::get('/help-center', [HelpCenterController::class, 'helpCenterPage'])->name('help.center.hero');
        Route::post('/update-hero', [HelpCenterController::class, 'updateHero'])->name('update.hero');
    });


    //category management
    Route::group(['prefix' => 'category'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('show.category.list');
        Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('category.delete');
    });

    //education manage
    Route::group(['prefix' => 'education'], function () {
        Route::get('/', [EducationController::class, 'index'])->name('show.education.list');
        Route::post('/store', [EducationController::class, 'store'])->name('education.store');
        Route::post('/update/{id}', [EducationController::class, 'update'])->name('education.update');
        Route::delete('/delete/{id}', [EducationController::class, 'destroy'])->name('education.delete');

        //pinned and unpinned education
        Route::post('/pin/{edu_id}', [EducationController::class, 'togglePinned'])->name('pinned.education');
    });

    //investment manage
    Route::group(['prefix' => 'investment'], function () {
        //asset class
        Route::get('/asset-class', [AssetClassController::class, 'index'])->name('show.asset.class.list');
        Route::post('/asset-class/store', [AssetClassController::class, 'store'])->name('asset.class.store');
        Route::post('/asset-class/update/{id}', [AssetClassController::class, 'update'])->name('asset.class.update');
        Route::delete('/asset-class/delete/{id}', [AssetClassController::class, 'destroy'])->name('asset.class.delete');

        //inventment type
        Route::get('/type', [InvestmentTypeController::class, 'index'])->name('show.investment.type.list');
        Route::post('/type/store', [InvestmentTypeController::class, 'store'])->name('investment.type.store');
        Route::post('/type/update/{id}', [InvestmentTypeController::class, 'update'])->name('investment.type.update');
        Route::delete('/type/delete/{id}', [InvestmentTypeController::class, 'destroy'])->name('investment.type.delete');

        //investment strategy
        Route::get('/strategy', [InvestmentStrategyController::class, 'index'])->name('show.investment.strategy.list');
        Route::post('/strategy/store', [InvestmentStrategyController::class, 'store'])->name('investment.strategy.store');
        Route::post('/strategy/update/{id}', [InvestmentStrategyController::class, 'update'])->name('investment.strategy.update');
        Route::delete('/strategy/delete/{id}', [InvestmentStrategyController::class, 'destroy'])->name('investment.strategy.delete');

        //investment
        Route::get('/', [InvestmentController::class,'index'])->name('get.investments');
        Route::get('/create', [InvestmentController::class,'create'])->name('create.investment');
        Route::post('/store', [InvestmentController::class,'store'])->name('store.investment');
        Route::get('/edit/{id}', [InvestmentController::class,'edit'])->name('edit.investment');
        Route::get('/update/{id}', [InvestmentController::class,'update'])->name('update.investment');
    });
});


Route::controller(FaqController::class)->group(function () {
    Route::get('/faq', 'index')->name('admin.faq.index');
    Route::get('/faq/create', 'create')->name('admin.faq.create');
    Route::post('/faq', 'store')->name('admin.faq.store');
    Route::get('/faq/edit/{id}', 'edit')->name('admin.faq.edit');
    Route::put('/faq/{id}', 'update')->name('admin.faq.update');
    Route::post('/faq/status/{id}', 'status')->name('admin.faq.status');
    Route::delete('/faq/{id}', 'destroy')->name('admin.faq.destroy');
});


//! Route for Profile Settings
Route::controller(ProfileController::class)->group(function () {
    Route::get('setting/profile', 'index')->name('setting.profile.index');
    Route::put('setting/profile/update', 'UpdateProfile')->name('setting.profile.update');
    Route::put('setting/profile/update/Password', 'UpdatePassword')->name('setting.profile.update.Password');
    Route::post('setting/profile/update/Picture', 'UpdateProfilePicture')->name('update.profile.picture');
});


Route::controller(DynamicPageController::class)->group(function () {
    Route::get('/dynamic-page', 'index')->name('admin.dynamic_page.index');
    Route::get('/dynamic-page/create', 'create')->name('admin.dynamic_page.create');
    Route::post('/dynamic-page/store', 'store')->name('admin.dynamic_page.store');
    Route::get('/dynamic-page/edit/{id}', 'edit')->name('admin.dynamic_page.edit');
    Route::put('/dynamic-page/update/{id}', 'update')->name('admin.dynamic_page.update');
    Route::post('/dynamic-page/status/{id}', 'status')->name('admin.dynamic_page.status');
    Route::delete('/dynamic-page/destroy/{id}', 'destroy')->name('admin.dynamic_page.destroy');
});


//! Route for Profile Settings
Route::controller(ProfileController::class)->group(function () {
    Route::get('setting/profile', 'index')->name('setting.profile.index');
    Route::put('setting/profile/update', 'UpdateProfile')->name('setting.profile.update');
    Route::put('setting/profile/update/Password', 'UpdatePassword')->name('setting.profile.update.Password');
    Route::post('setting/profile/update/Picture', 'UpdateProfilePicture')->name('update.profile.picture');
});


//! Route for Stripe Settings
Route::controller(SettingController::class)->group(function () {
    Route::get('setting/general', 'index')->name('setting.general.index');
    Route::patch('setting/general', 'update')->name('setting.general.update');
});


//CMS
Route::controller(AuthPageController::class)->prefix('cms')->name('cms.')->group(function () {
    Route::get('page/auth/section/bg', 'index')->name('page.auth.section.bg.index');
    Route::patch('page/auth/section/bg', 'update')->name('page.auth.section.bg.update');
});

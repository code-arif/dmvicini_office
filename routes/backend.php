<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\UserListController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\EducationController;
use App\Http\Controllers\Web\Backend\SubscriberController;
use App\Http\Controllers\Web\Backend\CMS\AuthPageController;
use App\Http\Controllers\Web\Backend\FooterManageController;
use App\Http\Controllers\Web\Backend\CMS\HelpCenterController;
use App\Http\Controllers\Web\Backend\Investment\InvestmentDesclaimerController;
use App\Http\Controllers\Web\Backend\InvestmentImportController;
use App\Http\Controllers\Web\Backend\Settings\ProfileController;
use App\Http\Controllers\Web\Backend\Settings\SettingController;
use App\Http\Controllers\Web\Backend\Settings\DynamicPageController;
use App\Http\Controllers\Web\Backend\Investment\AssetClassController;
use App\Http\Controllers\Web\Backend\Investment\InvestmentController;
use App\Http\Controllers\Web\Backend\Investment\InvestmentDocController;
use App\Http\Controllers\Web\Backend\Investment\InvestmentTypeController;
use App\Http\Controllers\Web\Backend\Investment\InvestmentStrategyController;
use App\Http\Controllers\Web\Backend\Investment\InvestmentHightlightController;
use App\Http\Controllers\Web\Backend\Investment\InvestTaxStrategyController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard'); // working
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats'); // working
    Route::get('/investments/{id}/details', [DashboardController::class, 'getInvestmentDetails']); // working
    Route::get('/users/{id}/details', [DashboardController::class, 'getUserDetails'])->name('user.details'); // working
    Route::post('/users/{id}/approve', [DashboardController::class, 'approveUser'])->name('user.approve'); // working
    // Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart');

    // Dashboard data for charts
    Route::get('dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');


    // investor management routes
    Route::prefix('investors')->name('investor.')->group(function () {
        // Investor List (DataTable AJAX)
        Route::get('/', [UserListController::class, 'index'])->name('list');

        // View Single Investor
        Route::get('/{id}', [UserListController::class, 'show'])->name('show');

        // Approve Investor
        Route::post('/approve', [UserListController::class, 'approve'])->name('approve');

        // Change Access Level
        Route::post('/change-access', [UserListController::class, 'changeAccessLevel'])->name('changeAccess');

        // Delete Investor
        Route::delete('/{id}', [UserListController::class, 'destroy'])->name('destroy');
    });


    // cms management
    Route::prefix('cms')->name('cms.')->group(function () {
        //help center page
        Route::get('/help-center', [HelpCenterController::class, 'helpCenterPage'])->name('help.center.hero');
        Route::post('/update-hero', [HelpCenterController::class, 'updateHero'])->name('update.hero');

        // footer management routes
        Route::get('/footer', [FooterManageController::class, 'index'])->name('footer.index');
        Route::post('/footer/update', [FooterManageController::class, 'update'])->name('footer.update');
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

    //investment tags
    Route::group(['prefix' => 'investment'], function () {
        // Asset Class Routes
        Route::get('/asset-class', [AssetClassController::class, 'index'])->name('show.asset.class.list');
        Route::get('/asset-class/get-all', [AssetClassController::class, 'getAllClasses'])->name('asset.class.get.all');
        Route::post('/asset-class/store', [AssetClassController::class, 'store'])->name('asset.class.store');
        Route::post('/asset-class/update/{id}', [AssetClassController::class, 'update'])->name('asset.class.update');
        Route::post('/asset-class/update-order', [AssetClassController::class, 'updateOrder'])->name('asset.class.update.order');
        Route::delete('/asset-class/delete/{id}', [AssetClassController::class, 'destroy'])->name('asset.class.delete');

        //inventment type
        Route::get('/type', [InvestmentTypeController::class, 'index'])->name('show.investment.type.list');
        Route::get('/type/get-all', [InvestmentTypeController::class, 'getAllClasses'])->name('investment.type.all');
        Route::post('/type/store', [InvestmentTypeController::class, 'store'])->name('investment.type.store');
        Route::post('/type/update/{id}', [InvestmentTypeController::class, 'update'])->name('investment.type.update');
        Route::post('/type/update-order', [InvestmentTypeController::class, 'updateOrder'])->name('investment.type.update.order');
        Route::delete('/type/delete/{id}', [InvestmentTypeController::class, 'destroy'])->name('investment.type.delete');

        // Investment Strategy
        Route::get('/strategy', [InvestmentStrategyController::class, 'index'])->name('show.investment.strategy.list');
        Route::get('/strategy/get-all', [InvestmentStrategyController::class, 'getAllStrategies'])->name('investment.strategy.all');
        Route::post('/strategy/store', [InvestmentStrategyController::class, 'store'])->name('investment.strategy.store');
        Route::post('/strategy/update/{id}', [InvestmentStrategyController::class, 'update'])->name('investment.strategy.update');
        Route::post('/strategy/update-order', [InvestmentStrategyController::class, 'updateOrder'])->name('investment.strategy.update.order');
        Route::delete('/strategy/delete/{id}', [InvestmentStrategyController::class, 'destroy'])->name('investment.strategy.delete');

        // Tax Strategy
        Route::get('/tax/strategy', [InvestTaxStrategyController::class, 'index'])->name('show.tax.strategy.list');
        Route::get('/tax/strategy/get-all', [InvestTaxStrategyController::class, 'getAllTaxStrategies'])->name('tax.strategy.all');
        Route::post('/tax/strategy/store', [InvestTaxStrategyController::class, 'store'])->name('tax.strategy.store');
        Route::post('/tax/strategy/update/{id}', [InvestTaxStrategyController::class, 'update'])->name('tax.strategy.update');
        Route::post('/tax/strategy/update-order', [InvestTaxStrategyController::class, 'updateOrder'])->name('tax.strategy.update.order');
        Route::delete('/tax/strategy/delete/{id}', [InvestTaxStrategyController::class, 'destroy'])->name('tax.strategy.delete');
    });

    // Route::prefix('deal')->name('investment.')->group(function () {
    //     Route::get('/list', [InvestmentController::class, 'index'])->name('list');
    //     Route::get('/create', [InvestmentController::class, 'create'])->name('create');
    //     Route::post('/store/basic', [InvestmentController::class, 'storeBasic'])->name('basic.store');
    //     Route::get('/edit/{id}', [InvestmentController::class, 'edit'])->name('edit');
    //     Route::post('/update/{id}', [InvestmentController::class, 'updateBasic'])->name('update');
    //     Route::delete('/delete/{id}', [InvestmentController::class, 'destroy'])->name('destroy');
    //     Route::post('/status/update', [InvestmentController::class, 'updateStatus'])->name('status.update');
    //     Route::get('/show/{id}', [InvestmentController::class, 'show'])->name('show'); // Changed from getInvestment

    //     // Investment highlight
    //     Route::post('/{id}/highlight', [InvestmentHightlightController::class, 'storeOrUpdateHighlight'])->name('highlight.store');

    //     // Investment documents
    //     Route::post('/{id}/document', [InvestmentDocController::class, 'uploadDocument'])->name('document.store');
    //     Route::delete('/document/{id}', [InvestmentDocController::class, 'deleteDocument'])->name('document.delete');

    //     // Investment images
    //     Route::post('/{id}/images', [InvestmentDocController::class, 'uploadImage'])->name('images.store');
    //     Route::delete('/image/{id}', [InvestmentDocController::class, 'deleteImage'])->name('image.delete');

    //     // Investment disclaimers
    //     Route::post('/{id}/disclaimer', [InvestmentDesclaimerController::class, 'storeOrUpdateDisclaimer'])->name('disclaimer.store');
    // });


    Route::prefix('deal')->name('investment.')->group(function () {
        Route::get('/list', [InvestmentController::class, 'index'])->name('list');
        Route::get('/create', [InvestmentController::class, 'create'])->name('create');
        Route::post('/store/basic', [InvestmentController::class, 'storeBasic'])->name('basic.store');
        Route::get('/edit/{id}', [InvestmentController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [InvestmentController::class, 'updateBasic'])->name('update');
        Route::delete('/delete/{id}', [InvestmentController::class, 'destroy'])->name('destroy');
        Route::post('/status/update', [InvestmentController::class, 'updateStatus'])->name('status.update');
        Route::get('/show/{id}', [InvestmentController::class, 'show'])->name('show');

        // IMPORTANT: Use {investment} instead of {id} for consistency
        Route::post('/{investment}/highlight', [InvestmentHightlightController::class, 'storeOrUpdateHighlight'])->name('highlight.store');
        Route::post('/{investment}/document', [InvestmentDocController::class, 'uploadDocument'])->name('document.store');
        Route::post('/{investment}/images', [InvestmentDocController::class, 'uploadImage'])->name('images.store');
        Route::post('/{investment}/disclaimer', [InvestmentDesclaimerController::class, 'storeOrUpdateDisclaimer'])->name('disclaimer.store');

        Route::delete('/document/{id}', [InvestmentDocController::class, 'deleteDocument'])->name('document.delete');
        Route::delete('/image/{id}', [InvestmentDocController::class, 'deleteImage'])->name('image.delete');
    });


    // Import routes
    Route::get('/investments/import', [InvestmentImportController::class, 'showImportForm'])
        ->name('investments.import.form');
    Route::post('/investments/import', [InvestmentImportController::class, 'import'])
        ->name('investments.import');
    Route::get('/investments/download-template', [InvestmentImportController::class, 'downloadTemplate'])
        ->name('investments.download-template');


    Route::get('/subscribers', [SubscriberController::class, 'index'])
        ->name('subscribers.index');
});


Route::controller(FaqController::class)->group(function () {
    Route::get('/faq', 'index')->name('admin.faq.index');
    Route::post('/faq/store', 'store')->name('admin.faq.store'); // Changed
    Route::put('/faq/update/{id}', 'update')->name('admin.faq.update'); // Changed
    Route::post('/faq/status/{id}', 'status')->name('admin.faq.status');
    Route::delete('/faq/destroy/{id}', 'destroy')->name('admin.faq.destroy'); // Changed
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
    Route::get('setting/profile', 'index')->name('setting.admin.profile.index');
    Route::post('setting/profile/update', 'UpdateProfile')->name('setting.admin.profile.update');
    Route::post('setting/profile/update/password', 'UpdatePassword')->name('setting.admin.rofile.update.password');
    Route::post('setting/profile/update/picture', 'UpdateProfilePicture')->name('update.admin.profile.picture');
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

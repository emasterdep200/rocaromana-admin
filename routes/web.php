<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PropertController;
use App\Http\Controllers\PropertysInquiryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\OutdoorFacilityController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportReasonController;
use App\Http\Controllers\SeoSettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\ComisionController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\AnuncioController;
use Illuminate\Support\Facades\Artisan;

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


Route::get('/', function () {
    return view('auth.login');
});

Route::get('customer-privacy-policy', [SettingController::class, 'show_privacy_policy'])->name('customer-privacy-policy');


Route::get('customer-terms-conditions', [SettingController::class, 'show_terms_conditions'])->name('customer-terms-conditions');


Auth::routes();

Route::get('privacypolicy', [HomeController::class, 'privacy_policy']);
Route::post('/webhook/razorpay', [WebhookController::class, 'razorpay']);
Route::post('/webhook/paystack', [WebhookController::class, 'paystack']);
Route::post('/webhook/paypal', [WebhookController::class, 'paypal']);
Route::post('/webhook/stripe', [WebhookController::class, 'stripe']);
Route::post('/webhook/payu', [WebhookController::class, 'payu']);



Route::middleware(['auth', 'checklogin','language'])->group(function () {

    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('view:cache');

    Route::get('render_svg', [HomeController::class, 'render_svg'])->name('render_svg');
    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'blank_dashboard'])->name('dashboard');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('about-us', [SettingController::class, 'index']);
    Route::get('privacy-policy', [SettingController::class, 'index']);
    Route::get('terms-conditions', [SettingController::class, 'index']);
    Route::get('system-settings', [SettingController::class, 'index']);
    Route::get('firebase_settings', [SettingController::class, 'index']);
    Route::get('app_settings', [SettingController::class, 'index']);
    Route::get('web_settings', [SettingController::class, 'index']);
    Route::get('system_version', [SettingController::class, 'index']);
    Route::post('firebase-settings', [SettingController::class, 'firebase_settings']);
    Route::post('app-settings', [SettingController::class, 'app_settings']);
    Route::get('system_version', [SettingController::class, 'system_version']);
    Route::post('web-settings', [SettingController::class, 'web_settings']);
    Route::get('notification-settings', [SettingController::class, 'notificationSettingIndex'])->name('notification-setting-index');
    Route::post('notification-settings', [SettingController::class, 'notificationSettingStore'])->name('notification-setting-store');

    Route::post('system_version_setting', [SettingController::class, 'system_version_setting']);

    /// START :: HOME ROUTE
    Route::get('change-password', [App\Http\Controllers\HomeController::class, 'change_password'])->name('changepassword');
    Route::post('check-password', [App\Http\Controllers\HomeController::class, 'check_password'])->name('checkpassword');
    Route::post('store-password', [App\Http\Controllers\HomeController::class, 'store_password'])->name('changepassword.store');
    Route::get('changeprofile', [HomeController::class, 'changeprofile'])->name('changeprofile');
    Route::post('updateprofile', [HomeController::class, 'update_profile'])->name('updateprofile');
    Route::post('firebase_messaging_settings', [HomeController::class, 'firebase_messaging_settings'])->name('firebase_messaging_settings');

    /// END :: HOME ROUTE

    /// START :: SETTINGS ROUTE

    Route::post('settings', [SettingController::class, 'settings']);
    Route::post('set_settings', [SettingController::class, 'system_settings']);
    /// END :: SETTINGS ROUTE

    /// START :: LANGUAGES ROUTE


    Route::resource('language', LanguageController::class);
    Route::get('language_list', [LanguageController::class, 'show']);
    Route::post('language_update', [LanguageController::class, 'update'])->name('language_update');
    Route::get('language-destory/{id}', [LanguageController::class, 'destroy'])->name('language.destroy');
    Route::get('set-language/{lang}', [LanguageController::class, 'set_language']);
    Route::get('download-panel-file', [LanguageController::class, 'downloadPanelFile'])->name('download-panel-file');
    Route::get('download-app-file', [LanguageController::class, 'downloadAppFile'])->name('download-app-file');
    Route::get('download-web-file', [LanguageController::class, 'downloadWebFile'])->name('download-web-file');

    /// END :: LANGUAGES ROUTE

    /// START :: PAYMENT ROUTE

    Route::get('getPaymentList', [PaymentController::class, 'get_payment_list']);
    Route::get('payment', [PaymentController::class, 'index']);
    /// END :: PAYMENT ROUTE

    /// START :: USER ROUTE

    Route::resource('users', UserController::class);
    Route::post('users-update', [UserController::class, 'update']);
    Route::post('users-reset-password', [UserController::class, 'resetpassword']);
    Route::get('userList', [UserController::class, 'userList']);
    Route::get('get_users_inquiries', [UserController::class, 'users_inquiries']);
    Route::get('users_inquiries', [UserController::class, function () {
        return view('users.users_inquiries');
    }]);
    Route::get('destroy_contact_request/{id}', [UserController::class, 'destroy_contact_request'])->name('destroy_contact_request');




    /// END :: PAYMENT ROUTE

    /// START :: PAYMENT ROUTE

    Route::resource('customer', CustomersController::class);
    Route::get('customerList', [CustomersController::class, 'customerList']);
    Route::post('customerstatus', [CustomersController::class, 'update'])->name('customer.customerstatus');
    /// END :: CUSTOMER ROUTE

    /// START :: SLIDER ROUTE

    Route::resource('slider', SliderController::class);
    Route::post('slider-order', [SliderController::class, 'update'])->name('slider.slider-order');
    Route::get('slider-destroy/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');
    Route::get('get-property-by-category', [SliderController::class, 'getPropertyByCategory'])->name('slider.getpropertybycategory');
    Route::get('sliderList', [SliderController::class, 'sliderList']);
    /// END :: SLIDER ROUTE

    /// START :: ARTICLE ROUTE

    Route::resource('article', ArticleController::class);
    Route::get('article_list', [ArticleController::class, 'show'])->name('article_list');
    Route::get('add_article', [ArticleController::class, 'create'])->name('add_article');
    Route::get('article-destroy/{id}', [ArticleController::class, 'destroy'])->name('article.destroy');
    /// END :: ARTICLE ROUTE

    /// START :: ADVERTISEMENT ROUTE

    Route::resource('featured_properties', AdvertisementController::class);
    Route::get('featured_properties_list', [AdvertisementController::class, 'show']);
    Route::post('featured_properties_status', [AdvertisementController::class, 'updateStatus'])->name('featured_properties.update-advertisement-status');
    Route::post('adv-status-update', [AdvertisementController::class, 'update'])->name('adv-status-update');
    /// END :: ADVERTISEMENT ROUTE

    /// START :: PACKAGE ROUTE
    Route::resource('package', PackageController::class);
    Route::get('package_list', [PackageController::class, 'show']);
    Route::post('package-update', [PackageController::class, 'update']);
    Route::post('package-status', [PackageController::class, 'updatestatus'])->name('package.updatestatus');
    Route::get('get_user_purchased_packages', [PackageController::class, function () {
        return view('packages.users_packages');
    }]);

    Route::get('get_user_package_list', [PackageController::class, 'get_user_package_list']);

    /// END :: PACKAGE ROUTE


    /// START :: CATEGORYW ROUTE
    Route::resource('categories', CategoryController::class);
    Route::get('categoriesList', [CategoryController::class, 'categoryList']);
    Route::post('categories-update', [CategoryController::class, 'update']);
    Route::post('categorystatus', [CategoryController::class, 'updateCategory'])->name('categorystatus');
    /// END :: CATEGORYW ROUTE


    /// START :: PARAMETER FACILITY ROUTE

    Route::resource('parameters', ParameterController::class);
    Route::get('parameter-list', [ParameterController::class, 'show']);
    Route::post('parameter-update', [ParameterController::class, 'update']);
    /// END :: PARAMETER FACILITY ROUTE

    /// START :: OUTDOOR FACILITY ROUTE
    Route::resource('outdoor_facilities', OutdoorFacilityController::class);
    Route::get('facility-list', [OutdoorFacilityController::class, 'show']);
    Route::post('facility-update', [OutdoorFacilityController::class, 'update']);
    Route::get('facility-delete/{id}', [OutdoorFacilityController::class, 'destroy'])->name('outdoor_facilities.destroy');
    /// END :: OUTDOOR FACILITY ROUTE


    /// START :: PROPERTY ROUTE
    Route::resource('property', PropertController::class);
    Route::get('getPropertyList', [PropertController::class, 'getPropertyList']);
    Route::post('updatepropertystatus', [PropertController::class, 'updateStatus'])->name('updatepropertystatus');
    Route::post('property-gallery', [PropertController::class, 'removeGalleryImage'])->name('property.removeGalleryImage');
    Route::get('get-state-by-country', [PropertController::class, 'getStatesByCountry'])->name('property.getStatesByCountry');
    Route::get('property-destory/{id}', [PropertController::class, 'destroy'])->name('property.destroy');
    Route::get('getFeaturedPropertyList', [PropertController::class, 'getFeaturedPropertyList']);
            Route::post('updateaccessability', [PropertController::class, 'updateaccessability'])->name('updateaccessability');

    Route::get('updateFCMID', [UserController::class, 'updateFCMID']);
    /// END :: PROPERTY ROUTE


    /// START :: PROPERTY INQUIRY
    Route::resource('property-inquiry', PropertysInquiryController::class);
    Route::get('getPropertyInquiryList', [PropertysInquiryController::class, 'getPropertyInquiryList']);
    Route::post('property-inquiry-status', [PropertysInquiryController::class, 'updateStatus'])->name('property-inquiry.updateStatus');
    /// ENND :: PROPERTY INQUIRY

    /// START :: REPORTREASON
    Route::resource('report-reasons', ReportReasonController::class);
    Route::get('report-reasons-list', [ReportReasonController::class, 'show']);
    Route::post('report-reasons-update', [ReportReasonController::class, 'update']);
    Route::get('report-reasons-destroy/{id}', [ReportReasonController::class, 'destroy'])->name('reasons.destroy');
    Route::get('users_reports', [ReportReasonController::class, 'users_reports']);
    Route::get('user_reports_list', [ReportReasonController::class, 'user_reports_list']);
    /// END :: REPORTREASON

    Route::resource('property-inquiry', PropertysInquiryController::class);


    /// START :: CHAT ROUTE

    Route::get('get-chat-list', [ChatController::class, 'getChats'])->name('get-chat-list');
    Route::post('store_chat', [ChatController::class, 'store']);
    Route::get('getAllMessage', [ChatController::class, 'getAllMessage']);
    /// END :: CHAT ROUTE


    /// START :: NOTIFICATION
    Route::resource('notification', NotificationController::class);
    Route::get('notificationList', [NotificationController::class, 'notificationList']);
    Route::get('notification-delete', [NotificationController::class, 'destroy']);
    Route::post('notification-multiple-delete', [NotificationController::class, 'multiple_delete']);
    /// END :: NOTIFICATION

    Route::resource('project', ProjectController::class);
    Route::post('updateProjectStatus', [ProjectController::class, 'updateStatus'])->name('updateProjectStatus');

    Route::resource('seo_settings', SeoSettingsController::class);
    Route::get('seo-settings-destroy/{id}', [SeoSettingsController::class, 'destroy'])->name('seo_settings.destroy');



    // Comercial
    Route::get('asesores', [AsesorController::class, 'index'])->name('asesores');
    Route::get('asesores_listing', [AsesorController::class, 'show'])->name('list_asesor');
    Route::get('comisiones', [ComisionController::class, 'index'])->name('comisiones');
    Route::get('list_comisiones', [ComisionController::class, 'show'])->name('list_comisiones');
    Route::get('comercial/dashboard', [VentaController::class, 'dashboard'])->name('comercial.dashboard');

    // Rutas para los anuncios
    Route::get('anuncios', [AnuncioController::class, 'index'])->name('anuncios');
    Route::get('anuncios_listing', [AnuncioController::class, 'show'])->name('list_anuncio');
    Route::post('anuncio_create', [AnuncioController::class, 'store'])->name('create_anuncio');
    Route::post('anuncio_update', [AnuncioController::class, 'update'])->name('update_anuncio');

    // Ruta Chat
    Route::get('chat', function () {
        return view('chat');
    });

    Route::get('calculator', function () {
        return view('Calculator.calculator');
    });
});

Auth::routes();


// // Add New Migration Route
// Route::get('/new-migrate', function () {
//     Artisan::call('migrate');
//     return redirect()->back();
// });

// // Rollback last step Migration Route
// Route::get('/migrate-rollback', function () {
//     Artisan::call('migrate:rollback');
//     return redirect()->back();
// });

// Clear Config
Route::get('/clear', function () {
    Artisan::call('config:clear');
    return redirect()->back();
});

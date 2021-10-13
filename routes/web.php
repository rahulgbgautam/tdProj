<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;


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

// ##################### Frontend Routes ####################
Route::group(['prefix' => '','middleware'=>'\App\Http\Middleware\CheckUserLogin'], function() {
	
	Route::get('/dashboard', 'Front\DashboardController@viewDashboard')->name('dashboard');
	Route::get('/home', 'Front\DashboardController@viewDashboard')->name('dashboard');    
	Route::get("profile-edit",'Front\ProfileController@edit')->name('profile-edit');
	Route::put("profile/update/{id}",'Front\ProfileController@update');
	Route::get('view-profile', 'Front\FrontController@viewProfile')->name('view-profile');
	Route::get("change-password",'Front\ProfileController@changePassword')->name('change-password');
	Route::put("profile-change-password/{id}",'Front\ProfileController@profileChangePassword')->name('profile-change-password');
	Route::get("deep-web-tool",'Front\MyDomainController@DeepWebTool')->name('deep-web-tool');
	Route::get("my-brands",'Front\MyDomainController@MyBrands')->name('my-brands');
	Route::get("my-portfolio",'Front\MyDomainController@MyPortfolio')->name('my-portfolio');
	Route::get("domain-detail/{id}",'Front\MyDomainController@domainDetail')->name('domain-detail');
	Route::get("domain-summary/{id}",'Front\MyDomainController@domainSummary')->name('domain-summary');
	Route::get("domain-auto-renewal/{id}",'Front\MyDomainController@domainAutoRenewal')->name('domain-auto-renewal');

	Route::get("subscription-plan/{id?}",'Front\SubscriptionPlanController@index')->name('subscription-plan');
	Route::get("subscription-domains",'Front\SubscriptionPlanController@subscriptionDomains')->name('subscription-domains');
	Route::post("subscription-domains-store",'Front\SubscriptionPlanController@subscriptionDomainsStore')->name('subscription-domains-store');
	Route::get("subscription-checkout",'Front\SubscriptionPlanController@subscriptionCheckout')->name('subscription-checkout');
	Route::post("subscription-payment",'Front\SubscriptionPlanController@subscriptionPayment')->name('subscription-payment');
	Route::get("subscription-success",'Front\SubscriptionPlanController@subscriptionSuccess')->name('subscription-success');

	Route::post('/validatePromoCodeAjax', 'Front\SubscriptionPlanController@validatePromoCodeAjax');


	Route::get("stripe",'Front\SubscriptionPlanController@stripePost')->name('stripe.post');

	// Route::get("subscription-free-domain",'Front\SubscriptionPlanController@subscriptionFreeDomain')->name('subscription-free-domain');
	// Route::post("subscription-purchase",'Front\SubscriptionPlanController@purchaseSubscription')->name('subscription-purchase');
	// Route::post("subscription-free-store",'Front\SubscriptionPlanController@subscriptionFreeStore')->name('subscription-free-store');

	Route::get("domain-rescan/{id}",'Front\ScanController@domainRescan');
	Route::get("domain-rescan-status/{id}",'Front\ScanController@domainRescanStatus');
	Route::match(['get', 'post'], "email-breach",'Front\ScanController@emailBreach')->name('email-breach');
	// Route::match(['get', 'post'], "email-breach-post",'Front\ScanController@emailBreachPost');
	// Route::get('view-report-pdf/{id}', 'PDFController@createPDF'); 

	Route::get("showCron/{command}/{param?}",'Front\SubscriptionPlanController@showCron');

});
Auth::routes();
Route::get('/', 'Front\FrontController@viewHome');
Route::post('/newsletterSubscribeAjax', 'Front\FrontController@newsletterSubscribeAjax');
Route::get("domain-rescan-status-name/{name}",'Front\ScanController@domainRescanStatusByName');
Route::get("pricing",'Front\SubscriptionPlanController@pricing')->name('pricing');

// Route::get('search-domain', 'Front\FrontController@searchDomain');
Route::get('search-domain', 'Front\ScanController@searchDomain');
Route::get('/verify-account', 'Front\FrontController@verifyAccount');
Route::get('product/{id}', 'Front\FrontController@productpages');
Route::get('resource/{id}', 'Front\FrontController@resourcepages');
Route::get('faq', 'Front\FrontController@faq')->name('faq');
Route::get('features', 'Front\FrontController@features')->name('features');
Route::get('about', 'Front\FrontController@about')->name('about');
Route::get('term', 'Front\FrontController@term')->name('term');
Route::get('privacy', 'Front\FrontController@privacy')->name('privacy');
Route::get('cookies', 'Front\FrontController@cookies')->name('cookies');
Route::get('activation-link/{id}', 'Front\FrontController@activationLink')->name('activation-link');

Route::get('view-report-pdf/{id}', 'Admin\PDFController@createPDF');
Route::get('scanDomainCron', 'Front\ScanController@scanDomainCron');
Route::get('checkApi/{domainIP}', 'Front\ScanController@checkApi');

Route::get('generate-invoice/{id}', 'Front\InvoicePdfController@createPDF');







// ##################### Admin Routes ####################
Route::get('admin','Admin\AdminController@index');

Route::group(['prefix' => 'admin'], function () {
    
    Route::view('forgot-password','admin.auth.forgotPassword');
	Route::post('forgot-password','Admin\AdminController@forgotPassword');
	Route::get('reset_password/{id}','Admin\AdminController@reset_password');
	Route::post('reset-password-process/{id}','Admin\AdminController@reset_password_process');
    Route::post('/login','Admin\AdminController@login');
	
	Route::group(["middleware"=>['admin_auth']],function(){

		Route::get('dashboard','Admin\AdminController@dashboard');
		Route::get('profile','Admin\AdminController@profile');
		Route::get("profile/edit/{id}",'Admin\AdminController@edit');
		Route::put("profile/update/{id}",'Admin\AdminController@update');
		Route::get("profile/change-password/{id}",'Admin\AdminController@profile_change_password');
		Route::put("profile/change-password-process/{id}",'Admin\AdminController@profile_change_password_process');
		
		Route::get('logout', function () {
	    Session::forget('admin');
	    	return redirect('admin');
		});

		Route::get('domains','Admin\DomainsController@index');
		Route::get('export-csv/{name?}', 'Admin\DomainsController@exportCsv');
		Route::get('add-user-domain/{id}','Admin\DomainsController@AssociateUser');
		Route::get('domains/create','Admin\DomainsController@create');
		Route::post('domains/store','Admin\DomainsController@store');
		Route::post('domains/associate/store','Admin\DomainsController@AssociateUserProcess');
		Route::get('domains/destroy/{id}','Admin\DomainsController@destroy');
		Route::get('block-domain/{id}','Admin\DomainsController@BlockDomain');
		Route::get('unblock-domain/{id}','Admin\DomainsController@UnblockDomain');
		Route::get('domain/edit/{id}','Admin\DomainsController@EditDomain');
		Route::put('domain/update/{id}','Admin\DomainsController@UpdateDomain');
		Route::get('view-report-pdf/{id}', 'Admin\PDFController@createPDF');
		Route::Resource('email-management','Admin\EmailTemplateController');
		Route::Resource('admin-management','Admin\AdminManagementController');
		
		Route::get('admin-management/createPermission/{id}','Admin\AdminManagementController@createPermission');
		Route::post('admin-management/Permissions/store/{id}','Admin\AdminManagementController@permissionsStore');

		Route::Resource('user-management','Admin\UserManagementController');
		Route::Resource('content-management','Admin\ContentManagementController');
		Route::Resource('banner-management','Admin\BannerController');
		Route::Resource('features-management','Admin\FeaturesController');
		Route::Resource('faq','Admin\FaqController');
		Route::Resource('settings','Admin\SettingsController');
		Route::Resource('dynamic-content','Admin\DynamicContentController');
		Route::Resource('probs-category','Admin\ProbsCategoryController');
		Route::Resource('probs-sub-category','Admin\ProbsSubCategoryController');
		Route::Resource('transaction-history','Admin\UserSubscriptionController');
		Route::Resource('industry','Admin\IndustryController');
		Route::Resource('overall-rating','Admin\OverallRatingController');
		Route::Resource('news-letter','Admin\NewsLetterController');
		Route::Resource('promo-code','Admin\PromoCodeController');
		Route::get('user-management/free-access/{id}','Admin\UserManagementController@provideFreeAccess');

		Route::get("domain-rescan/{id}",'Front\ScanController@domainRescanByAdmin');
		// Route::get('transaction-history/{email}','Admin\UserSubscriptionController@index');
	});
	
});
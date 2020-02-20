<?php
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

Auth::routes();
Route::group(['middleware'=>['auth']], function(){

    Route::match(['get', 'post'], '/', 'NewDashboardControllers@index')->name('dashboard.index');

    // Master
    Route::group(['prefix'=>'master'], function(){

        Route::resource('document', 'MDocumentsControllers');
        Route::get('table/document', 'MDocumentsControllers@dataTable')->name('table.document');

        //Notaris
        Route::resource('notaris', 'MNotarisControllers');
        Route::get('table/notaris', 'MNotarisControllers@dataTable')->name('table.notaris');

        //Services
        Route::resource('services', 'MServicesControllers');
        Route::get('table/services', 'MServicesControllers@dataTable')->name('table.services');

        //Category
        Route::resource('category', 'MCategoryControllers');
        Route::get('table/category', 'MCategoryControllers@dataTable')->name('table.category');

        //Service Document
        Route::resource('service-document', 'ServiceDocumentControllers');
        Route::get('table/service-document', 'ServiceDocumentControllers@dataTable')->name('table.service-document');
        Route::get('table/document-related/{id}', 'ServiceDocumentControllers@documentRelated')->name('table.document-related');

        //Service Category
        Route::resource('service-category', 'ServiceCategoryControllers');
        Route::get('table/service-category', 'ServiceCategoryControllers@dataTable')->name('table.service-category');
        Route::get('table/category-related/{id}', 'ServiceCategoryControllers@categoryRelated')->name('table.category-related');

        // Role
        Route::resource('role', 'SecRoleController');
        Route::get('table/role', 'SecRoleController@dataTable')->name('table.role');
        
        /* --------------- EMPLOYEE -------------------- */
        Route::resource('employee', 'MstEmployeeController');
        Route::get('table/employee', 'MstEmployeeController@dataTable')->name('table.employee');
        Route::post('employee/fetch', 'MstEmployeeController@fetch')->name('employee.fetch');
        Route::get('employee/delete/{id}', 'MstEmployeeController@delete')->name('employee.delete');
       
    });

	// Transaction
    Route::group(['prefix'=>'transaction'], function(){

        /* --------------- TRC FSK -------------------- */
        Route::resource('fsk', 'TrcFskControllers');
        Route::get('table/fsk', 'TrcFskControllers@dataTable')->name('table.fsk');
        Route::post('fsk/fsk-getCategory', 'TrcFskControllers@getCategory')->name('fsk.fsk-getCategory');

        /* --------------- TRC FSK CLIENT-------------------- */
        Route::resource('fsk-client', 'FskClientControllers');
        Route::get('table/fsk-client', 'FskClientControllers@dataTable')->name('table.fsk-client');

        /* --------------- TRC FSK DEBITUR-------------------- */
        Route::resource('fsk-debitur', 'FskClientDebiturControllers');

        /* --------------- FSK Create Service -------------------- */
        Route::resource('fsk-service', 'ServiceCartControllers');
        Route::post('fsk-service/fetch', 'ServiceCartControllers@fetch')->name('fsk-service.fetch');
        Route::get('fsk-service/getServiceName/{id}', 'ServiceCartControllers@getServiceName')->name('fsk-service.getServiceName');



    	/* --------------- INVESTOR -------------------- */
	    Route::resource('investor', 'MstInvestorController');
	    Route::get('table/investor', 'MstInvestorController@dataTable')->name('table.investor');
        Route::get('table/investor2', 'MstInvestorController@dataTable2')->name('table.investor2');
        Route::get('investor/delete/{id}', 'MstInvestorController@delete')->name('investor.delete');
        Route::get('investor/topup/{id}', 'MstInvestorController@topupform')->name('investor.topupform');
	    Route::get('investor/cashout/{id}', 'MstInvestorController@cashoutform')->name('investor.cashoutform');
        Route::get('investor/quick/{id}', 'MstInvestorController@quickShow')->name('investor.quick');
        // Investor -> internet banking
	    Route::get('table/internet-banking/{id}', 'InvestorInternetBankingController@dataTable')->name('table.internet-banking');
	    Route::get('internet-banking/create','InvestorInternetBankingController@create')->name('internet-banking.create');
	    Route::post('internet-banking/store', 'InvestorInternetBankingController@store')->name('internet-banking.store');
        Route::get('internet-banking/edit/{id}', 'InvestorInternetBankingController@edit')->name('internet-banking.edit');
        Route::put('internet-banking/update/{id}', 'InvestorInternetBankingController@update')->name('internet-banking.update');
        Route::get('internet-banking/destroy/{id}', 'InvestorInternetBankingController@destroy')->name('internet-banking.destroy');
        // Investor -> atm
	    Route::get('table/atm/{id}', 'InvestorAtmController@dataTable')->name('table.atm');
	    Route::get('atm/create','InvestorAtmController@create')->name('atm.create');
	    Route::post('atm/store', 'InvestorAtmController@store')->name('atm.store');
        Route::get('atm/edit/{id}', 'InvestorAtmController@edit')->name('atm.edit');
        Route::put('atm/update/{id}', 'InvestorAtmController@update')->name('atm.update');
        Route::get('atm/destroy/{id}', 'InvestorAtmController@destroy')->name('atm.destroy');
        /* --------------- INVESTOR / BALANCE -------------------- */
	    Route::get('balance/balance-pane', 'InvestorBalanceController@pane')->name('balance.pane');
	    Route::get('table/balance/{id}', 'InvestorBalanceController@dataTable')->name('table.balance');
        Route::get('balance/get/{id}', 'InvestorBalanceController@getBalance')->name('balance.get');
        Route::get('balance/getinvest/{id}', 'InvestorBalanceController@getInvest')->name('balance.getInvest');
        Route::get('balance/topup-form/{id}', 'InvestorBalanceController@topupForm')->name('balance.topup-form');
        Route::post('balance/topup', 'InvestorBalanceController@topup')->name('balance.topup');
        Route::get('balance/cashout-form/{id}', 'InvestorBalanceController@cashoutForm')->name('balance.cashout-form');
        // Investor -> transaction
	    Route::get('transaction/transaction-pane', 'InvestorTransactionController@pane')->name('transaction.pane');
	    Route::get('table/transaction/{id}', 'InvestorTransactionController@dataTable')->name('table.transaction');
        Route::get('widget/transaction/{id}', 'InvestorTransactionController@widget')->name('widget.transaction');
        // Investor -> asset
        Route::get('investor-asset/asset-pane', 'InvestorAssetController@assetPane')->name('investor-asset.pane');
	    Route::get('table/investor-asset/{id}', 'InvestorAssetController@assetDataTable')->name('table.investor-asset');
        Route::get('investor-asset/detail/{id}', 'InvestorAssetController@assetDetail')->name('investor-asset.detail');
        // Investor -> asset fav
        Route::get('investor-asset/fav-asset-pane', 'InvestorAssetController@assetFavPane')->name('investor-asset-fav.pane');
        Route::get('table/investor-asset-fav/{id}', 'InvestorAssetController@assetFavDataTable')->name('table.investor-asset-fav');
        // Investor -> summary
        Route::get('investor/summary-pane/{id}', 'MstInvestorController@summaryPane')->name('investor.summary-pane');
        // Investor Referral
        Route::get('investor/referral/{id}','MstInvestorController@referral')->name('referral.pane');
        Route::get('investor/referral/parent/{id}','MstInvestorController@referralParent')->name('referral.parent');
        Route::get('investor/referral/child/{id}','MstInvestorController@referralChild')->name('referral.child');

        /* --------------- MONITORING TOPUP -------------------- */
        Route::resource('monitoring-topup', 'MonitoringtopupController');
        Route::get('table/monitoring-topup/{start}/{end}', 'MonitoringtopupController@dataTable')->name('table.MonitoringTopup');
	    Route::get('table/monitoring-topup-history/{id}', 'MonitoringtopupController@dataHistory')->name('table.topup.history');
        Route::get('monitoring-topup/verify/{id}', 'MonitoringtopupController@verify')->name('monitoring-topup.verify');
        Route::post('monitoring-topup/reject', 'MonitoringtopupController@reject')->name('monitoring-topup.reject');
        Route::get('widget/monitoring-topup', 'MonitoringtopupController@reloadWidget')->name('monitoring-topup.widget');
        Route::post('monitoring-topup/recheck', 'MonitoringtopupController@recheck')->name('monitoring-topup.recheck');

        /* --------------- MONITORING CASH OUT -------------------- */
        Route::resource('monitoring-cashout', 'MonitoringTopupOutController');
        Route::get('table/monitoring-cashout/{start}/{end}', 'MonitoringTopupOutController@dataTable')->name('table.MonitoringTopupOut');
        Route::get('table/monitoring-cashout-history/{id}', 'MonitoringTopupOutController@dataHistory')->name('table.cashout.history');
        Route::get('monitoring-cashout/process/{id}', 'MonitoringTopupOutController@process')->name('monitoring-cashout.process');
        Route::get('monitoring-cashout/verify/{id}', 'MonitoringTopupOutController@verify')->name('monitoring-cashout.verify');
        Route::post('monitoring-cashout/reject', 'MonitoringTopupOutController@reject')->name('monitoring-cashout.reject');
        Route::get('widget/monitoring-cashout', 'MonitoringTopupOutController@reloadWidget')->name('monitoring-cashout.widget');
        
        /* --------------- OFFERING PURCHASE -------------------- */
        Route::resource('offering-purchase', 'OfferingPurchaseController');
        Route::get('table/offering-purchase', 'OfferingPurchaseController@dataTable')->name('table.offering-purchase');
        Route::get('offering-purchase/reply/{id}', 'OfferingPurchaseController@reply')->name('offering-purchase.reply');

        /* --------------- MONITORING VA BCA Inquiry -------------------- */
        Route::resource('vabca-inquiry', 'VABCAInquiryController');
        Route::get('table/vabca-inquiry', 'VABCAInquiryController@dataTable')->name('table.vabca-inquiry');
        Route::get('vabca-inquiry/detail/{id}', 'VABCAInquiryController@detail')->name('vabca-inquiry.detail');
        Route::get('widget/vabca-inquiry', 'VABCAInquiryController@reloadWidget')->name('vabca-inquiry.widget');

        /* --------------- MONITORING INVESTMENT -------------------- */
        Route::resource('investment', 'InvestmentController');
        Route::get('table/investment/{start}/{end}/{filter}', 'InvestmentController@dataTable')->name('table.MonitoringInvestment');
    });
        
    // Setting
    Route::group(['prefix'=>'setting'], function(){
       // Module
        Route::resource('menu', 'SecModuleController');
        Route::get('table/menu', 'SecModuleController@dataTable')->name('table.SecModule');
        //Access Level
		Route::resource('access-level', 'SecAccessLevelController');
		Route::get('table/access-level', 'SecAccessLevelController@dataTable')->name('table.access-level'); 
		Route::get('table/detail-access-level', 'SecAccessLevelController@dataTableDetail')->name('table.detail-access-level');
		Route::post('access-level/add-act', 'SecAccessLevelController@addAct')->name('access-level.add-act'); 
		Route::post('access-level/remove-act', 'SecAccessLevelController@removeAct')->name('access-level.remove-act'); 
    });
	
	// Route::get('/master/country','CountryController@index');
	// Route::get('/master/country/create','CountryController@create');
	// Route::post('/master/country/post_country','CountryController@store');
	// Route::get('/master/country/edit/{id}','CountryController@edit');
	// Route::post('/master/country/edit_country/{id}','CountryController@update');
	// Route::get('/master/country/import','CountryController@import');
	// Route::post('/master/country/proses_import','CountryController@proses_import');

	Route::get('/master/province','ProvinceController@index');
	Route::get('/master/province/create','ProvinceController@create');
	Route::post('/master/province/post_province','ProvinceController@store');
	Route::post('/master/province/edit_province/{id}','ProvinceController@update');
	Route::get('/master/province/delete/{id}','ProvinceController@destroy');
	Route::get('/master/province/edit/{id}','ProvinceController@edit');
	Route::get('/master/province/delete/{id}','ProvinceController@destroy');
	Route::get('/master/province/import','ProvinceController@import');
	Route::post('/master/province/import_province','ProvinceController@import_province');
	
	/*Route::get('/master/regency','RegencyController@index');
	Route::get('/master/regency/create','RegencyController@create');
	Route::post('/master/regency/post_regency','RegencyController@store');
	Route::get('/master/regency/edit_regency/{id}','RegencyController@edit');
	Route::post('/master/regency/regency_edit/{id}','RegencyController@update');
	Route::get('/master/regency/delete_regency/{id}','RegencyController@destroy');
	Route::post('/master/regency/regency_edit/{id}','RegencyController@update');
	Route::get('/master/regency/import','RegencyController@import');
	Route::post('/master/regency/import_post_regency','RegencyController@import_regency');*/

	
	Route::get('/master/data_address','MstAddressController@index');
	Route::get('/master/address/create','MstAddressController@create');
	Route::get('/master/address/province/{id}','MstAddressController@province');
	Route::get('/master/address/regency/{id}','MstAddressController@regency');
	Route::get('/master/address/district/{id}','MstAddressController@district');
	Route::get('/master/address/village/{id}','MstAddressController@village');
	Route::post('/master/address/post_address','MstAddressController@store');

	/* --------------- MONITORING CASH OUT -------------------- */
	// Route::resource('master/category-news', 'MstNewsCategoryController');
	// Route::get('table/category-news', 'MstNewsCategoryController@dataTable')->name('table.BlgCategoryNews');
	// Route::resource('master/category-news', 'BlgCategoryNewsController');
	// Route::get('table/category-news', 'BlgCategoryNewsController@dataTable')->name('table.BlgCategoryNews');
	
	// Blg News
	// Route::resource('master/news', 'BlgNewsController');
	// Route::get('table/news', 'BlgNewsController@dataTable')->name('table.BlgNews');
	// Route::get('news/setposition/{id}','BlgNewsController@setposition')->name('banner/bannerposition');
	// Route::get('news/delete/{id}', 'BlgNewsController@delete')->name('news.delete');
	
	

	//Contact Us
	// Route::resource('master/contact-us', 'MstContactUsController');
	// Route::get('table/contact-us', 'MstContactUsController@dataTable')->name('table.MstContactUs');
	
	//Tag News
	// Route::resource('master/tag-news', 'MstTagNewsController');
	// Route::get('table/tag-news', 'MstTagNewsController@dataTable')->name('table.MstTagNews');

	Route::resource('master/class-price', 'MstClassPriceController');
	Route::get('table/class-price', 'MstClassPriceController@dataTable')->name('table.MstClassPrice');

	

	// Route::resource('master/security-guide', 'SecurityGuideController');
	// Route::get('table/security-guide', 'SecurityGuideController@dataTable')->name('table.SecurityGuide');
	// Route::get('security-guide/delete/{id}', 'SecurityGuideController@delete')->name('security-guide.delete');
 //    Route::get('security-guide/show-detail/{id}', 'SecurityGuideController@show_detail')->name('security-guide.show-detail');
 //    Route::put('security-guide/update-data/{id}', 'SecurityGuideController@updateData')->name('security-guide.update-data');

	// Route::resource('master/partner', 'MstPartnerController');
	// Route::get('table/partner', 'MstPartnerController@dataTable')->name('table.MstPartner');
	// Route::get('partner/delete/{id}', 'MstPartnerController@delete')->name('partner.delete');

	Route::resource('master/voucher', 'MstVoucherController');
	Route::get('table/voucher', 'MstVoucherController@dataTable')->name('table.MstVoucher');
	Route::get('voucher/delete/{id}', 'MstVoucherController@delete')->name('voucher.delete');
    Route::get('voucher/detail/{id}', 'MstVoucherController@detail')->name('voucher.detail');
    Route::get('voucher/edit_detail/{id}', 'MstVoucherController@edit_detail')->name('voucher.edit_detail');
    Route::get('voucher/show-detail/{id}', 'MstVoucherController@show_detail')->name('voucher.show-detail');
    Route::put('voucher/update-satu/{id}', 'MstVoucherController@update_satu')->name('voucher.update-satu');

    Route::get('investor/random-investment/{id}', 'MstInvestorController@randomInvestment');

    Route::group(['prefix' => 'kpr'], function () {

        Route::resource('kpr-asset', 'KprController');
        Route::get('table/asset', 'KprController@dataTable')->name('table.kpr.asset');
        Route::get('asset/delete/{id}', 'KprController@delete')->name('kpr.asset.delete');
        Route::post('asset/upload', 'KprController@upload')->name('kpr.asset.upload');
        Route::post('asset/uploadFeatured', 'KprController@uploadFeatured')->name('kpr.asset.uploadFeatured');
        Route::post('asset/remove-attr-img', 'KprController@removeAttrImg')->name('kpr.asset.remove-attr');
        Route::get('asset-detail/detail-pane', 'KprController@assetPane')->name('kpr.asset-detail.pane');
        Route::get('investor-asset/asset-pane', 'KprController@investorPane')->name('kpr.investor.pane');
        Route::get('table/kpr-investor/{id}', 'KprController@dataTableKprInv')->name('table.investor.kpr');
        Route::get('kpr-asset-detail/show-detail/{id}', 'KprController@show_detail')->name('kpr.investor.show-detail');

        Route::resource('installment', 'TrcInstallmentController');
        Route::get('table/installment', 'TrcInstallmentController@dataTable')->name('table.kpr.installment');

        // Route::get('asset', 'KprAssetController@index')->name('kpr.asset.index');
        // Route::get('asset/create', 'KprAssetController@create')->name('kpr.asset.create');
        // Route::post('asset/store', 'KprAssetController@store')->name('kpr.asset.store');
        // Route::get('asset/edit/{id}', 'KprAssetController@edit')->name('kpr.asset.edit');
        // Route::put('asset/update/{id}', 'KprAssetController@update')->name('kpr.asset.update');
        // Route::put('asset/delete/{id}', 'KprAssetController@delete')->name('kpr.asset.delete');
        // Route::get('asset/data', 'KprAssetController@data')->name('kpr.asset.data');

        Route::get('booking', 'KprBookingController@index')->name('kpr.booking.index');
        Route::get('booking/data', 'KprBookingController@data')->name('kpr.booking.data');
        Route::get('booking/survey/{id}', 'KprBookingController@survey')->name('kpr.booking.survey');
        Route::put('booking/assign-survey/{id}', 'KprBookingController@assign')->name('kpr.booking.assign');
        Route::get('booking/widget', 'KprBookingController@widget');
        Route::get('booking/detail/{id}', 'KprBookingController@detail')->name('kpr.booking.detail');
        Route::put('booking/assign/{id}', 'KprBookingController@assign')->name('kpr.booking.assign');
        Route::get('booking/approve-form/{id}','KprBookingController@approveForm')->name('kpr.booking.approve-form');
        Route::put('booking/approve/{id}', 'KprBookingController@approve')->name('kpr.booking.approve');
        Route::get('booking/reject-form/{id}', 'KprBookingController@rejectForm')->name('kpr.booking.reject-form');
        Route::put('booking/reject/{id}', 'KprBookingController@reject')->name('kpr.booking.reject');
        Route::get('booking/cancel/{id}', 'KprBookingController@cancel')->name('kpr.booking.cancel');
        Route::put('booking/cancel/{id}', 'KprBookingController@cancelling')->name('kpr.booking.cancelling');
    });

    Route::group(['prefix' => 'apt'], function () {

        Route::resource('apt-asset', 'MstAptAssetControllers');
        Route::get('table/asset', 'MstAptAssetControllers@dataTable')->name('table.apt.asset');
        Route::post('asset/file-asset', 'MstAptAssetControllers@file_asset')->name('apt.asset.file-asset');
        Route::post('asset/denah', 'MstAptAssetControllers@denah')->name('apt.asset.denah');
        Route::post('asset/featuredImg', 'MstAptAssetControllers@featured')->name('apt.asset.featured');
        Route::post('asset/images', 'MstAptAssetControllers@images')->name('apt.asset.images');
        Route::get('asset/delete/{id}', 'MstAptAssetControllers@delete')->name('apt.asset.delete');
        Route::get('asset-detail/detail-pane', 'MstAptAssetControllers@assetPane')->name('apt.asset-detail.pane');
        Route::get('unit-detail/unit-pane/{id}', 'MstAptAssetControllers@assetUnitPane')->name('apt.asset-unit.pane');
        Route::get('table/apt-unit/{id}', 'MstAptAssetControllers@dataTableUnit')->name('table.unit.apt');
        Route::post('asset/remove-file-attachment', 'MstAptAssetControllers@removeAttrFile')->name('apt.asset.remove-file');
        Route::post('asset/remove-attr-image', 'MstAptAssetControllers@removeAttrImage')->name('apt.asset.remove-image');

        Route::resource('unit-asset', 'MstUnitControllers');
        Route::get('unit-asset/new/{id}','MstUnitControllers@createUnit')->name('unit-asset.unitnew');

        // Booking
        Route::get('booking', 'AptBookingController@index')->name('booking');
        Route::get('booking/data', 'AptBookingController@data')->name('booking.data');
        Route::get('booking/widget', 'AptBookingController@widget');
        Route::get('booking/detail/{id}', 'AptBookingController@detail')->name('booking.detail');
        Route::get('booking/form-interview/{id}', 'AptBookingController@formInterview')->name('booking.interview.form');
        Route::put('booking/interview/{id}', 'AptBookingController@interview')->name('booking.interview');
        Route::get('booking/form-approve/{id}', 'AptBookingController@formApprove')->name('booking.approve.form');
        Route::put('booking/approve/{id}', 'AptBookingController@approve')->name('booking.approve');
        Route::get('booking/form-reject/{id}', 'AptBookingController@formReject')->name('booking.reject.form');
        Route::put('booking/reject/{id}', 'AptBookingController@reject')->name('booking.reject');
        Route::get('booking/form-cancel/{id}', 'AptBookingController@formCancel')->name('booking.cancel.form');
        Route::put('booking/cancel/{id}', 'AptBookingController@cancel')->name('booking.cancel');

        Route::resource('floor', 'MstDenahAptControllers');
        Route::get('table/floor/{id}', 'MstDenahAptControllers@dataTable')->name('table.apt.floor');
        Route::get('table/floor-unit/{id}', 'MstDenahAptControllers@dataFloorUnit')->name('table.apt.floor.unit');
        Route::get('unit-floor/new/{id}','MstDenahAptControllers@createFloor')->name('floor.unit.create');
        Route::post('floor/cekData', 'MstDenahAptControllers@cekData')->name('floor.cekData');

        
    });

    Route::group(['prefix' => 'ecommerce'], function () {

        Route::resource('product', 'MstEcommerceProductControllers');
        Route::get('table/product', 'MstEcommerceProductControllers@dataTable')->name('table.ecommerce.product');
        Route::post('product/featuredImg', 'MstEcommerceProductControllers@featured')->name('product.ecommerce.featured');
        Route::post('product/images', 'MstEcommerceProductControllers@images')->name('product.ecommerce.images');
        Route::post('product/fetch-attributes', 'MstEcommerceProductControllers@fetchAttributes')->name('product.ecommerce.fetch-attributes');
        Route::get('product/delete/{id}', 'MstEcommerceProductControllers@delete')->name('product.ecommerce.delete');
        Route::post('product/remove-attr-image', 'MstEcommerceProductControllers@removeAttrImage')->name('product.remove-image');
        Route::get('product/getvalue/{id}', 'MstEcommerceProductControllers@getvalue')->name('product.ecommerce.getvalue');
        Route::get('product/getChild/category', 'MstEcommerceProductControllers@getChild')->name('product.ecommerce.getChild');

        Route::get('product/getCategory/{id}', 'MstEcommerceProductControllers@getCategory')->name('product.ecommerce.getcategory');

        Route::post('product/show-image', 'MstEcommerceProductControllers@showImageFeatured')->name('product.ecommerce.show_image');
        Route::post('product/show-images', 'MstEcommerceProductControllers@showImages')->name('product.ecommerce.show_images');

        Route::resource('product-category', 'MstProductCategoryControllers');
        Route::get('table/product-category', 'MstProductCategoryControllers@dataTable')->name('table.product-category');
        Route::post('product-category/icon', 'MstProductCategoryControllers@iconCategory')->name('product.category.icon');


        Route::resource('product-tenor', 'MstTenorProductController');
        Route::get('table/product-tenor', 'MstTenorProductController@dataTable')->name('table.product-tenor');


        Route::resource('atribute-product', 'MstAttributeProductController');
        Route::get('table/atribute-product', 'MstAttributeProductController@dataTable')->name('table.product-attribute');


        Route::resource('order', 'TrcOrderEcommerceController');
        Route::get('table/order', 'TrcOrderEcommerceController@dataTable')->name('table.order.ecommerce');
        Route::get('order/process/{id}', 'TrcOrderEcommerceController@processOrder')->name('order.process');
        Route::get('order/getdetail/{id}', 'TrcOrderEcommerceController@getDetail')->name('order.getdetail');
        Route::get('order/invoice-pane', 'TrcOrderEcommerceController@invoicePane')->name('order.invoice.pane');
        Route::get('order/history-pane/{id}', 'TrcOrderEcommerceController@historyPane')->name('order.history.pane');
        Route::get('table/order-history/{id}', 'TrcOrderEcommerceController@dataTableHistory')->name('table.history.order');
        Route::get('order/ajax-getstatus/{id}', 'TrcOrderEcommerceController@getStatus')->name('order.get-status.pane');

        Route::get('order/log-order/{id}', 'TrcOrderEcommerceController@logOrder')->name('order.log');
        Route::get('order/order-print/{id}', 'TrcOrderEcommerceController@print')->name('order.print');

        Route::post('order/log-save', 'TrcOrderEcommerceController@logSave')->name('order.log.save');


        // Route::get('table/order/{id}', 'TrcOrderEcommerceController@detailProduct')->name('table.order.detail.product');
        

    });

});





// Route::get('/home', 'HomeController@index')->name('home');

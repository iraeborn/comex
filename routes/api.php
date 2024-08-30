<?php

use Illuminate\Http\Request;

use App\Http\Middleware\RqlgAuth;
use App\Http\Middleware\RqlgAuthAPI;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

// Route::post('/register', 'UserController@register');
Route::get('/users', 'UserApiController@allUsers');
Route::get('/users/clients', 'UserApiController@clients');
Route::get('/users/admins', 'UserApiController@admins');
Route::get('/users/exporters', 'UserApiController@exporters');
Route::get('/users/carriers', 'UserApiController@carriers');
Route::get('/users-by-role', 'UserApiController@getByRole');

Route::get('/user/{any}', 'UserApiController@get')->where('any', '\d+');
Route::post('/user', 'UserApiController@post');
Route::put('/user', 'UserApiController@put');
Route::delete('/user/{any}', 'UserApiController@delete')->where('any', '\d+');


Route::post('/link-orders-provider', 'ProviderApiController@linkOrdersProvider');
Route::post('/unlink-orders-provider', 'ProviderApiController@unlinkOrderProvider');

Route::get('/orders', 'OrderApiController@index')->middleware(RqlgAuthAPI::class);

Route::get('/list-orders', 'OrderApiController@listOrders')->middleware(RqlgAuthAPI::class);

Route::get('/orders/details', 'OrderApiController@details')->middleware(RqlgAuthAPI::class);
Route::get('/orders/railway', 'OrderApiController@railway')->middleware(RqlgAuthAPI::class);
Route::get('/orders/containers', 'OrderApiController@containers')->middleware(RqlgAuthAPI::class);
Route::get('/orders/reports', 'OrderApiController@reports')->middleware(RqlgAuthAPI::class);
Route::get('/order/{any}', 'OrderApiController@get')->where('any', '\d+');
Route::post('/order', 'OrderApiController@newOrder')->middleware(RqlgAuthAPI::class);
Route::put('/order/{any}', 'OrderApiController@put')->where('any', '\d+');
Route::delete('/order/{any}', 'OrderApiController@delete')->where('any', '\d+');
Route::post('/orders/{id}/contract-provisions', 'OrderApiController@storeContractProvision');
Route::put('/orders/{id}/contract-provisions/{contractID}', 'OrderApiController@updateContractProvision');
Route::delete('/orders/{id}/contract-provisions/{contractID}', 'OrderApiController@deleteContractProvision');

Route::post('/order/{orderId}/note', 'OrderApiController@storeNote');
Route::post('/order/{orderId}/date', 'OrderApiController@storeDate');
Route::post('/order/{orderId}/dhl', 'OrderApiController@storeDhl');

Route::get('/order/historyLogs', 'OrderApiController@getHistoryLogs');

Route::get('/orders/calendarEvents', 'OrderApiController@getCalendarEvents');

Route::post('/order/container/{containerId}', 'OrderApiController@updateContainers');

#graph dashboard
Route::get('/orders-last-few-months', 'API\OrdersController@ordersLastFewMonths')->middleware(RqlgAuthAPI::class);
Route::get('/items-last-few-months', 'API\OrdersController@itemsLastFewMonths')->middleware(RqlgAuthAPI::class);

Route::get('/my-orders', 'OrderApiController@myOrders')->middleware(RqlgAuthAPI::class);
Route::put('/my-orders', 'OrderApiController@myOrdersPut')->middleware(RqlgAuthAPI::class);

Route::get('/order/{type}/{status}/{document}/{hash}', 'OrderApiController@setDocumentoStatus');

Route::get('/items', 'ItemApiController@index');

Route::get('/item/{any}', 'ItemApiController@get')->where('any', '\d+');
Route::post('/item', 'ItemApiController@post');
Route::put('/item', 'ItemApiController@put');
Route::delete('/item/{any}', 'ItemApiController@delete')->where('any', '\d+');


Route::get('/shipping/{any}', 'ShippingApiController@get')->where('any', '\d+');
Route::post('/shipping', 'ShippingApiController@post');
Route::put('/shipping/{any}', 'ShippingApiController@put')->where('any', '\d+');
Route::delete('/shipping/{any}', 'ShippingApiController@delete')->where('any', '\d+');

Route::post('/shipping/accept/{any}', 'ShippingApiController@accept')->middleware(RqlgAuthAPI::class)->where('any', '\d+');
Route::post('/shipping/reject/{any}', 'ShippingApiController@reject')->middleware(RqlgAuthAPI::class)->where('any', '\d+');

Route::get('/container/{any}', 'ContainerApiController@get')->where('any', '\d+');
Route::post('/container', 'ContainerApiController@post');
Route::put('/container/{any}', 'ContainerApiController@put')->where('any', '\d+');
Route::delete('/container/{any}', 'ContainerApiController@delete')->where('any', '\d+');

Route::get('/drafts', 'DraftApiController@index');
Route::get('/draft/{any}', 'DraftApiController@get')->where('any', '\d+')->middleware(RqlgAuthAPI::class);
Route::put('/draft/{any}', 'DraftApiController@put')->where('any', '\d+');
Route::put('/draft/status', 'DraftApiController@statusChange');

Route::get('/loading/{any}', 'LoadingApiController@index')->where('any', '\d+');
Route::post('/loading/{any}', 'LoadingApiController@post')->where('any', '\d+');

Route::post('/generateLoadingDocs/{any}', 'LoadingApiController@generateDocs')->where('any', '\d+');


Route::get('/ports', 'PortsApiController@index');
Route::put('/ports', 'PortsApiController@put');

Route::get('/settings', 'SettingsApiController@get');
Route::put('/settings', 'SettingsApiController@put');
Route::get('/email-test', 'SettingsApiController@emailTest');

Route::put('/document', 'DocumentApiController@put');

Route::get('/railroad/{any}', 'RailroadApiController@index')->where('any', '\d+');
Route::put('/railroad/{any}', 'RailroadApiController@put')->where('any', '\d+');
Route::get('/railwayObservation/{any}', 'RailroadApiController@getObservation')->where('any', '\d+')->middleware(RqlgAuthAPI::class);
Route::post('/railwayObservation/{any}', 'RailroadApiController@addObservation')->where('any', '\d+')->middleware(RqlgAuthAPI::class);


Route::get('/orders-seguro', 'OrderApiController@getCertificateOfSecurity')->middleware(RqlgAuthAPI::class);
Route::get('/orders-fumigation', 'OrderApiController@getCertificateOfFumigation')->middleware(RqlgAuthAPI::class);
Route::get('/orders-quality', 'OrderApiController@getCertificateOfQuality')->middleware(RqlgAuthAPI::class);
Route::get('/orders-weight', 'OrderApiController@getCertificateOfWeight')->middleware(RqlgAuthAPI::class);
Route::get('/orders-inspection', 'OrderApiController@getInspectionAgency')->middleware(RqlgAuthAPI::class);
Route::get('/orders-mapa', 'OrderApiController@getMapa')->middleware(RqlgAuthAPI::class);
Route::get('/orders-terminal', 'OrderApiController@getTerminal')->middleware(RqlgAuthAPI::class);
Route::get('/orders-railway', 'OrderApiController@getRailway')->middleware(RqlgAuthAPI::class);

Route::post('/orders-seguro', 'OrderApiController@postCertificateOfSecurity')->middleware(RqlgAuthAPI::class);
Route::post('/orders-fumigation', 'OrderApiController@postCertificateOfFumigation')->middleware(RqlgAuthAPI::class);
Route::post('/orders-quality', 'OrderApiController@postCertificateOfQuality')->middleware(RqlgAuthAPI::class);
Route::post('/orders-weight', 'OrderApiController@postCertificateOfWeight')->middleware(RqlgAuthAPI::class);
Route::post('/orders-inspection', 'OrderApiController@postInspectionAgency')->middleware(RqlgAuthAPI::class);
Route::post('/orders-mapa', 'OrderApiController@postMapa')->middleware(RqlgAuthAPI::class);
Route::post('/orders-terminal', 'OrderApiController@postTerminal')->middleware(RqlgAuthAPI::class);
Route::post('/orders-railway', 'OrderApiController@postRailway')->middleware(RqlgAuthAPI::class);

Route::get('/order-mapa/{any}', 'OrderApiController@getMapaById')->middleware(RqlgAuthAPI::class)->where('any', '\d+');
Route::post('/order-mapa/{any}', 'OrderApiController@postMapaById')->middleware(RqlgAuthAPI::class)->where('any', '\d+');

Route::get('/original-documents/{any}', 'OriginalDocumentApiController@index')->where('any', '\d+');
Route::put('/original-documents/{any}', 'OriginalDocumentApiController@put')->where('any', '\d+');

Route::get('/stuffing/{any}', 'ContainerStuffingApiController@index')->where('any', '\d+');
Route::put('/stuffing/{any}', 'ContainerStuffingApiController@put')->where('any', '\d+');

Route::get('/bill/{any}', 'BillsApiController@index')->where('any', '\d+');
Route::put('/bill/{any}', 'BillsApiController@put')->where('any', '\d+');
Route::post('/bill/{any}', 'BillsApiController@post')->where('any', '\d+');

Route::get('/fumigation/{any}', 'FumigationApiController@index')->where('any', '\d+');
Route::put('/fumigation/{any}', 'FumigationApiController@put')->where('any', '\d+');
Route::get('/fumigationObservation/{any}', 'FumigationApiController@getObservation')->where('any', '\d+')->middleware(RqlgAuthAPI::class);
Route::post('/fumigationObservation/{any}', 'FumigationApiController@addObservation')->where('any', '\d+')->middleware(RqlgAuthAPI::class);
Route::put('/fumigation-profile/{any}', 'FumigationApiController@putProfile')->where('any', '\d+');

Route::get('/vehicle', 'VehicleApiController@index');
Route::post('/vehicle', 'VehicleApiController@post');

Route::post('/vehicle/{truckId}/update', 'VehicleApiController@updateVehicle');

Route::get('/inspection/{any}', 'InspectionAgencyApiController@index')->where('any', '\d+');
Route::post('/inspection/{any}', 'InspectionAgencyApiController@post')->where('any', '\d+');
Route::get('/inspectionObservation/{any}', 'InspectionAgencyApiController@getObservation')->where('any', '\d+')->middleware(RqlgAuthAPI::class);
Route::post('/inspectionObservation/{any}', 'InspectionAgencyApiController@addObservation')->where('any', '\d+')->middleware(RqlgAuthAPI::class);
Route::put('/inspection-profile/{any}', 'InspectionAgencyApiController@putProfile')->where('any', '\d+');

Route::get('/insurance/{any}', 'InsuranceAgencyApiController@index')->where('any', '\d+');
Route::post('/insurance/{any}', 'InsuranceAgencyApiController@post')->where('any', '\d+');
Route::get('/insuranceObservation/{any}', 'InsuranceAgencyApiController@getObservation')->where('any', '\d+')->middleware(RqlgAuthAPI::class);
Route::post('/insuranceObservation/{any}', 'InsuranceAgencyApiController@addObservation')->where('any', '\d+')->middleware(RqlgAuthAPI::class);

Route::get('/insurance-profile/{any}', 'InsuranceAgencyApiController@getProfile')->where('any', '\d+');
Route::put('/insurance-profile/{any}', 'InsuranceAgencyApiController@putProfile')->where('any', '\d+');

Route::get('/mapa/{any}', 'MapaApiController@index')->where('any', '\d+');
Route::post('/mapa/{any}', 'MapaApiController@post')->where('any', '\d+');
Route::get('/mapaObservation/{any}', 'MapaObservationApiController@index')->where('any', '\d+')->middleware(RqlgAuthAPI::class);
Route::post('/mapaObservation/{any}', 'MapaObservationApiController@post')->where('any', '\d+')->middleware(RqlgAuthAPI::class);
Route::put('/forwarding-profile/{any}', 'MapaApiController@putProfile')->where('any', '\d+');

Route::get('/tracking/{any}', 'TrackingApiController@index')->where('any', '.*');

Route::get('/containers/{any}', 'ContainerStuffingApiController@getContainers')->where('any', '\d+');
Route::put('/containers/{any}', 'ContainerStuffingApiController@putContainers')->where('any', '\d+');
Route::get('/containerObservation/{any}', 'ContainerObservationApiController@index')->where('any', '\d+')->middleware(RqlgAuthAPI::class);
Route::post('/containerObservation/{any}', 'ContainerObservationApiController@post')->where('any', '\d+')->middleware(RqlgAuthAPI::class);
Route::put('/containers-stuffing/{any}', 'ContainerStuffingApiController@putContainersStuffingData')->where('any', '\d+');

Route::post('/upload', 'ApiController@upload');

Route::get('/transaction-types', 'TransactionApiController@getTransactionTypeList');
Route::put('/transaction-type', 'TransactionApiController@putTransactionTypeList');
Route::put('/transaction-type/{any}', 'TransactionApiController@putTransactionTypeList');
Route::delete('/transaction-type/{any}', 'TransactionApiController@deleteTransactionTypeList');

Route::get('/balances', 'TransactionApiController@index');
Route::get('/balances-client', 'TransactionApiController@indexClient')->middleware(RqlgAuthAPI::class)->where('any', '\d+');
Route::get('/balance/{any}', 'TransactionApiController@get')->middleware(RqlgAuthAPI::class)->where('any', '\d+');
Route::post('/balance/{any}', 'TransactionApiController@post')->middleware(RqlgAuthAPI::class)->where('any', '\d+');
Route::put('/balance/{any}', 'TransactionApiController@put')->middleware(RqlgAuthAPI::class)->where('any', '\d+');
Route::delete('/balance/{any}/{id}', 'TransactionApiController@deleteTransaction')->middleware(RqlgAuthAPI::class)->where('any', '\d+')->where('id', '\d+');
Route::post('/balance/posting', 'TransactionApiController@storePosting')->middleware(RqlgAuthAPI::class)->where('any', '\d+');
Route::put('/balance/posting/{any}', 'TransactionApiController@updatePosting')->middleware(RqlgAuthAPI::class)->where('any', '\d+');
Route::delete('/balance/posting/{any}', 'TransactionApiController@deletePosting')->middleware(RqlgAuthAPI::class)->where('any', '\d+');

Route::get('/report', 'TransactionApiController@report');
Route::get('/reportPdf', 'TransactionApiController@transactionsReportPDF');

Route::get('/traking-links', 'TrackingApiController@getTrackingLinks')->middleware(RqlgAuthAPI::class);
Route::post('/traking-links/{any}', 'TrackingApiController@postTrackingLinks')->middleware(RqlgAuthAPI::class)->where('any', '\d+');


Route::get('/reset', 'ApiController@reloadSystem');
Route::get('/update', 'ApiController@updateSystem');

Route::get('/siscomex', 'SiscomexApiController@index');
Route::get('/sendmail/{any}', 'UserApiController@sendEmail')->where('any', '\d+');
Route::get('/sendmail-register/{any}', 'UserApiController@sendEmailRecovery')->where('any', '.*');
Route::get('/resendmail/{any}', 'UserApiController@sendEmailResend')->where('any', '\d+');

Route::post('/update-transaction/{any}', 'OrderApiController@updateTransaction')->where('any', '\d+');

Route::get('/specifications', 'SpecificationsApiController@getSpecifications');
Route::get('/specifications/{any}', 'SpecificationsApiController@get')->where('any', '\d+');
Route::post('/specifications', 'SpecificationsApiController@post')->middleware(RqlgAuthAPI::class);
Route::put('/specifications/{any}', 'SpecificationsApiController@put')->where('any', '\d+');
Route::delete('/specifications/{any}', 'SpecificationsApiController@delete')->where('any', '\d+');

Route::get('/order-document', 'DocumentOrderApiController@getOrderDocuments');
Route::get('/order-document/{any}', 'DocumentOrderApiController@get')->where('any', '\d+');
Route::get('/order-document-copy/{any}', 'DocumentOrderApiController@copy')->where('any', '\d+');
Route::post('/order-document', 'DocumentOrderApiController@post')->middleware(RqlgAuthAPI::class);
Route::put('/order-document/{any}', 'DocumentOrderApiController@put')->where('any', '\d+');
Route::delete('/order-document/{any}', 'DocumentOrderApiController@delete')->where('any', '\d+');

Route::get('/banks', 'BanksApiController@getBanks');
Route::get('/banks/{any}', 'BanksApiController@get')->where('any', '\d+');
Route::post('/banks', 'BanksApiController@post')->middleware(RqlgAuthAPI::class);
Route::put('/banks/{any}', 'BanksApiController@put')->where('any', '\d+');
Route::delete('/banks/{any}', 'BanksApiController@delete')->where('any', '\d+');

Route::get('/signatures/{id?}', 'SignaturesController@index');
Route::post('/signatures', 'SignaturesController@post')->middleware(RqlgAuthAPI::class);
Route::put('/signatures/{any}', 'SignaturesController@put')->where('any', '\d+');
Route::delete('/signatures/{any}', 'SignaturesController@delete')->where('any', '\d+');


Route::get('/drivers', 'DriversApiController@getDrivers');
Route::get('/drivers/{any}', 'DriversApiController@get')->where('any', '\d+');
Route::post('/drivers', 'DriversApiController@post')->middleware(RqlgAuthAPI::class);
Route::put('/drivers/{any}', 'DriversApiController@put')->where('any', '\d+');
Route::delete('/drivers/{any}', 'DriversApiController@delete')->where('any', '\d+');


Route::get('/groups/{any?}', 'GroupsApiController@get')->where('any', '\d+');
Route::post('/groups/{any?}', 'GroupsApiController@post')->where('any', '\d+');
Route::post('/groups/smtp-test', 'GroupsApiController@emailTest');
Route::put('/groups/{any}', 'GroupsApiController@post')->where('any', '\d+');
Route::delete('/groups/{any}', 'GroupsApiController@delete')->where('any', '\d+');

Route::get('/services/{any?}', 'ServicesApiController@get')->where('any', '\d+');
Route::post('/services/{any?}', 'ServicesApiController@post')->where('any', '\d+');
Route::delete('/services/{any}', 'ServicesApiController@delete')->where('any', '\d+');

Route::get('/providers/contracts', 'API\ProviderContractsController@index');
Route::get('/providers/{id}/contracts', 'API\ProviderContractsController@show');
Route::post('/providers/{id}/contracts', 'API\ProviderContractsController@store');
Route::put('/providers/{id}/contracts', 'API\ProviderContractsController@update');
Route::delete('/providers/{id}/contracts', 'API\ProviderContractsController@delete');


//Route::get('/services/provisions', 'API\ServiceProvisionsController@index');
Route::get('contracts/services/factor-types', 'API\ContractServicesController@factorTypes');

Route::get('/contracts/provisions', 'API\ContractProvisionsController@index');
Route::get('/provisions/{id}/postings', 'API\ContractProvisionsController@show');
Route::post('/provisions/{id}/postings', 'API\ContractProvisionsController@storePosting');
Route::put('/provisions/{provisionID}/postings/{postingID}', 'API\ContractProvisionsController@updatePosting');
Route::delete('/provisions/{provisionID}/postings/{postingID}', 'API\ContractProvisionsController@deletePosting');
Route::delete('/provisions/{provisionID}', 'API\ContractProvisionsController@deleteProvision');

Route::get('/reports/dre', 'API\OrdersController@dreReport');
Route::get('/reports/dre/export', 'API\OrdersController@dreReportPDF');
Route::get('/reports/pending_orders/export', 'API\OrdersController@pendingOrdersReportPDF');
Route::get('/reports/truck_loading/export', 'API\OrdersController@truckLoadingReportPDF');
Route::get('/reports/receivable_orders/export', 'API\OrdersController@receivableOrdersReportPDF');
Route::get('/reports/reports/export', 'API\OrdersController@reportsReportPDF');
Route::get('/reports/provisions/export', 'API\OrdersController@provisionsReportPDF');
Route::get('/reports/invoices/export', 'API\OrdersController@invoicesReportPDF');
Route::get('/reports/invoices', 'API\ProvisionPostingsController@invoicesReport');

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

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RqlgAuth;
use App\Order;

// Route::get('/order/{id}/shippingssss', function ($id) {


//     $order = Order::with([
//         'consignee',
//         'container_stuffing',
//         'items',
//         'loadings',
//         'mapa',
//         'owner',
//         'shipping'
//     ])->find($id);

//     if (!$order) return;


//     $data = $order;

//     return view('documents.shipping')->with(['data' => ['order' => $data]]);
//     // return view('documents.stuffing_loading.commercial_invoice');
// });


Route::get('/order/{id}/draft-bill', 'API\OrdersController@generateDraftBill');
// Route::get('/order/{id}/draft-bill', 'GenerateDocumentsController@draftBill');

Route::get('/order/{id}/load-unitization', 'API\OrdersController@generateLoadUnitization');
Route::get('/order/{id}/commercial-invoice', 'API\OrdersController@generateCommercialInvoice');
Route::get('/order/{id}/packing-list', 'API\OrdersController@generatePackingList');
Route::get('/order/{id}/fumigation-declaration', 'API\OrdersController@generateFumigationDeclaration');
Route::get('/order/{id}/phyto-certificate', 'API\OrdersController@generatePhytoCertificate');
Route::get('/order/{id}/bl-draft', 'API\OrdersController@generateBLDraft');


// Route::get('/unitizacao-carga', function () {

//     $order = Order::with([
//         'bills',
//         'consignee',
//         'container_stuffing',
//         'items',
//         'loadings',
//         'mapa',
//         'owner',
//         'shipping',
//         'shipping.containers'
//     ])->find(109);

//     $data = $order;

//     return view('documents.stuffing_loading.load_unitization_draft')->with(['cargoData' => $data]);
// });


// Route::get('/commercial-invoice', function () {

//     $order = Order::with([
//         'bills',
//         'consignee',
//         'container_stuffing',
//         'items',
//         'loadings',
//         'mapa',
//         'owner',
//         'shipping',
//         'shipping.containers',
//         'transactions' => function ($query) {
//             $query->where('transaction_type_id', '=', 2);
//         }
//     ])->find(109);

//     $data = $order;

//     return view('documents.stuffing_loading.commercial_invoice')
//         ->with([
//             'cargoData' => $data,
//             'secondaryText' => true
//         ]);
// });

// Route::get('/packing-list', function () {

//     $order = Order::with([
//         'bills',
//         'consignee',
//         'container_stuffing',
//         'items',
//         'loadings',
//         'mapa',
//         'owner',
//         'shipping',
//         'shipping.containers'
//     ])->find(109);

//     $data = $order;

//     return view('documents.stuffing_loading.packing_list')
//         ->with([
//             'cargoData' => $data,
//             'secondaryText' => true
//         ]);
// });

// Route::get('/teste-pdf', function () {


//     $order = Order::with([
//         'consignee',
//         'container_stuffing',
//         'items',
//         'loadings',
//         'notify',
//         'owner',
//         'shipping'
//     ])->find(109);

//     $data = $order;

//     return view('documents.stuffing_loading.invoice_draft')->with(['cargoData' => $data]);
//     // return view('documents.stuffing_loading.commercial_invoice');
// });


//Route::get('/', ['as' => 'index', 'uses' => 'UsersController@index']);
Route::get('/', [UsersController::class, 'index'])->name('home.get');
Route::post('/', [UsersController::class, 'doLogin'])->name('home.post');
//Route::post('/', ['as' => 'index', 'uses' => 'UsersController@doLogin']);

Route::get('/logout', ['as' => 'index', 'uses' => 'UsersController@doLogout']);

// Route::get('/change-password', ['as' => 'index', 'uses' => 'UsersController@changePassword']);
Route::get('forgot-password', [UsersController::class, 'forgotPassword'])->name('forgot-password');
// Route::post('/change-password', ['as' => 'index', 'uses' => 'UsersController@updatePassword']);

Route::get('/{any}', 'SpaController@index')->where('any', '^(?!api).*$')->middleware(RqlgAuth::class);

Route::post('/groups/smtp-teste', 'GroupsApiController@emailTest');

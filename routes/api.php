<?php

use App\Http\Controllers\Api\MasterData\MasterSupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MasterData\MasterUnitController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/login', 'Api\AuthController@login');
Route::post('/logout', 'Api\AuthController@logout');

// add Command Export
Route::get('/get-warehouse-and-unit', 'Api\ExportMaterialsController@get_warehouse_and_unit');
Route::get('/get-list-materials-in-warehouse', 'Api\ExportMaterialsController@get_list_materials_in_warehouse');
Route::post('/export-add', 'Api\ExportMaterialsController@export_add');


// Get Command Export
Route::get('/command-export', 'Api\ExportMaterialsController@command_export');
Route::post('/sucess-command-export', 'Api\ExportMaterialsController@sucess_command_export');
// Route::get('/command-export-detail', 'Api\ExportMaterialsController@command_export_detail');
Route::get('/decryption-label', 'Api\ExportMaterialsController@decryption');
Route::post('/export-materials', 'Api\ExportMaterialsController@export_materials');

// transfer
Route::get('/decryption-box-transfer', 'Api\ExportMaterialsController@data_box_transfer');
Route::post('/transfer-materials', 'Api\ExportMaterialsController@transfer_materials');

// Inventory
// Get List Command
Route::get('/command-inventory', 'Api\InventoriesController@command_inventory');
Route::get('/detail-inven', 'Api\InventoriesController@detail_inven');
Route::post('/update-inventory', 'Api\InventoriesController@update_inventory');
Route::post('/sucess-inventory', 'Api\InventoriesController@success');

//order
Route::get('/order-detail', 'Api\ProductivityController@order_detail');
Route::post('/update-order', 'Api\ProductivityController@update_order');

//check infor
Route::get('/check-infor', 'Api\CheckInforController@check_infor');


// retype materials
Route::get('/decryption-box', 'Api\ImportMaterialsController@decryption_box');
Route::post('/retype-materials', 'Api\ImportMaterialsController@retype_add');

// import packing list 
Route::get('/get-data-input-pallet', 'Api\ImportMaterialsController@get_data_input_pallet');
Route::post('/import-packing-list', 'Api\ImportMaterialsController@import_packing_list');

// update location
Route::get('/get-data-update-location', 'Api\ImportMaterialsController@get_data_update_location');
Route::post('/update-location', 'Api\ImportMaterialsController@update_location');



// masterdata
Route::prefix('settings')->group(function () {
    Route::prefix('unit')->group(function () {
        Route::get('/', [MasterUnitController::class, 'index']);
        // Route::get('/history', [MasterUnitController::class, 'history']);
    });
    Route::prefix('supplier')->group(function () {
        Route::get('/', [MasterSupplierController::class, 'index']);
        // Route::get('/history', [MasterUnitController::class, 'history']);
    });
});

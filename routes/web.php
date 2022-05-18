<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::group(['middleware' => 'language'], function()
{
    Route::get('/change-language/{language}', 'HomeController@change_language')->name('home.changeLanguage');
	Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');


	Route::get('/', 'HomeController@welcome');
	Route::prefix('/dashboard')->group(function()
	{
		Route::get('/', 'Dashboard\DashboardController@get_api_chart')->name('dashboard.apiChart');
	});

	Auth::routes(['register' => false]);

	// Master Data
	Route::prefix('/setting')->middleware('role:view_master')->group(function()
	{
		// Unit
		Route::prefix('/setting-unit')->group(function()
		{
			Route::get('/', 'MasterData\MasterUnitController@index')->name('masterData.unit');
			Route::get('/show', 'MasterData\MasterUnitController@show')->name('masterData.unit.show')->middleware(['role:create_master', 'role:update_master']);
			Route::post('/filter', 'MasterData\MasterUnitController@filter')->name('masterData.unit.filter');
			Route::post('/add-or-update', 'MasterData\MasterUnitController@add_or_update')->name('masterData.unit.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
			Route::get('/destroy', 'MasterData\MasterUnitController@destroy')->name('masterData.unit.destroy')->middleware('role:delete_master');;
			Route::post('/import-file-excel', 'MasterData\MasterUnitController@import_file_excel')->name('masterData.unit.importFileExcel')->middleware(['role:create_master', 'role:import_master']);
			Route::get('/export_file', 'MasterData\MasterUnitController@export_file')->name('masterData.unit.export_file')->middleware(['role:create_master', 'role:export_master']);

		});
		

		// Supplier
		Route::prefix('/setting-supplier')->group(function()
		{
			
			Route::get('/', 'MasterData\MasterSupplierController@index')->name('masterData.supplier');
			Route::get('/show', 'MasterData\MasterSupplierController@show')->name('masterData.supplier.show')->middleware(['role:create_master', 'role:update_master']);
			Route::post('/filter', 'MasterData\MasterSupplierController@filter')->name('masterData.supplier.filter');
			Route::post('/add-or-update', 'MasterData\MasterSupplierController@add_or_update')->name('masterData.supplier.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
			Route::get('/destroy', 'MasterData\MasterSupplierController@destroy')->name('masterData.supplier.destroy')->middleware('role:delete_master');

			Route::post('/import-file-excel', 'MasterData\MasterSupplierController@import_file_excel')->name('masterData.supplier.importFileExcel')->middleware(['role:create_master', 'role:import_master']);

			Route::get('/export_file', 'MasterData\MasterSupplierController@export_file')->name('masterData.supplier.export_file')->middleware(['role:create_master', 'role:export_master']);

		});

		// Materials
		Route::prefix('/setting-materials')->group(function()
		{
			Route::get('/', 'MasterData\MasterMaterialsController@index')->name('masterData.materials');
			Route::get('/show', 'MasterData\MasterMaterialsController@show')->name('masterData.materials.show')->middleware(['role:create_master', 'role:update_master']);
			Route::post('/filter', 'MasterData\MasterMaterialsController@filter')->name('masterData.materials.filter');
			Route::post('/add-or-update', 'MasterData\MasterMaterialsController@add_or_update')->name('masterData.materials.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
			Route::post('/import-file-excel', 'MasterData\MasterMaterialsController@import_file_excel')->name('masterData.materials.importFileExcel')->middleware(['role:create_master', 'role:import_master']);
			Route::get('/destroy', 'MasterData\MasterMaterialsController@destroy')->name('masterData.materials.destroy')->middleware('role:delete_master');

			Route::get('/export_file', 'MasterData\MasterMaterialsController@export_file')->name('masterData.materials.export_file')->middleware(['role:create_master', 'role:export_master']);
		});

		// Group Materials
		Route::prefix('/setting-group-materials')->group(function()
		{
			Route::get('/', 'MasterData\MasterGroupMaterialsController@index')->name('masterData.groupMaterials');
			Route::get('/show', 'MasterData\MasterGroupMaterialsController@show')->name('masterData.groupMaterials.show')->middleware(['role:create_master', 'role:update_master']);
			Route::post('/filter', 'MasterData\MasterGroupMaterialsController@filter')->name('masterData.groupMaterials.filter');
			Route::post('/add-or-update', 'MasterData\MasterGroupMaterialsController@add_or_update')->name('masterData.groupMaterials.addOrUpdate')
			->middleware(['role:create_master', 'role:update_master']);
			Route::post('/import-file-excel', 'MasterData\MasterGroupMaterialsController@import_file_excel')->name('masterData.groupMaterials.importFileExcel')->middleware(['role:create_master', 'role:import_master']);
			Route::get('/destroy', 'MasterData\MasterGroupMaterialsController@destroy')->name('masterData.groupMaterials.destroy')->middleware('role:delete_master');
		});

		// Product
		Route::prefix('/setting-product')->group(function()
		{
			Route::get('/', 'MasterData\MasterProductController@index')->name('masterData.product');
			Route::get('/show', 'MasterData\MasterProductController@show')->name('masterData.product.show')->middleware(['role:create_master', 'role:update_master']);
			Route::get('/filter-bom', 'MasterData\MasterProductController@get_id_bom_and_materials')->name('masterData.product.getIdBomAndMaterials');
			Route::post('/filter', 'MasterData\MasterProductController@filter')->name('masterData.product.filter');
			Route::post('/add-or-update', 'MasterData\MasterProductController@add_or_update')->name('masterData.product.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
			Route::get('/destroy', 'MasterData\MasterProductController@destroy')->name('masterData.product.destroy')->middleware('role:delete_master');
			Route::post('/import_file', 'MasterData\MasterProductController@import_file')->name('masterData.product.import_file');

			Route::get('/export_file', 'MasterData\MasterProductController@export_file')->name('masterData.product.export_file')->middleware(['role:create_master', 'role:export_master']);

		});

		
		// Setting Warehouse
		Route::prefix('/setting-warehouse')->group(function()
		{
			Route::get('/', 'MasterData\MasterWarehouseController@index')->name('masterData.warehouses');
			Route::get('/show', 'MasterData\MasterWarehouseController@show')->name('masterData.warehouses.show')->middleware(['role:create_master', 'role:update_master']);
			Route::get('/test-led', 'MasterData\MasterWarehouseController@test_led')->name('masterData.warehouses.testLed');
			Route::get('/turn-on-off-led', 'MasterData\MasterWarehouseController@turn_on_off_led')->name('masterData.warehouses.turnOnOffLed')->middleware(['role:create_master', 'role:update_master']);
			Route::get('/filter-detail', 'MasterData\MasterWarehouseController@filter_detail')->name('masterData.warehouses.filterDetail');
			Route::get('/filter-detail-one', 'MasterData\MasterWarehouseController@filter_detail_one')->name('masterData.warehouses.filterDetailOne');
			Route::post('/add-or-update', 'MasterData\MasterWarehouseController@add_or_update')->name('masterData.warehouses.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
			Route::post('/add-or-update-detail', 'MasterData\MasterWarehouseController@add_or_update_detail')->name('masterData.warehouses.addOrUpdateDetail');
			// ->middleware(['role:create_master', 'role:update_master']);
			Route::post('/add-or-update-type', 'MasterData\MasterWarehouseController@add_or_update_type')->name('masterData.warehouses.addOrUpdateType')->middleware(['role:create_master', 'role:update_master']);
			Route::get('/destroy', 'MasterData\MasterWarehouseController@destroy')->name('masterData.warehouses.destroy')->middleware('role:delete_master');
			Route::get('/filter-detail1', 'MasterData\MasterWarehouseController@filter_detail1')->name('masterData.warehouses.filterDetail1');
			
			Route::get('/filter_warehouse', 'MasterData\MasterWarehouseController@filter_warehouse')->name('masterData.warehouses.filter_warehouse');
			Route::get('/get_list_materials_in_warehouse', 'MasterData\MasterWarehouseController@get_list_materials_in_warehouse')->name('masterData.warehouses.get_list_materials_in_warehouse');
			Route::get('/filter-history', 'WarehouseSystem\TransferController@filter_history')->name('masterData.warehouses.filter_history');
		});

		

	});
	Route::prefix('/warehouse-system')->group(function()
	{
		Route::prefix('/import')->group(function()
		{
			Route::get('/', 'WarehouseSystem\ImportController@import')->name('warehousesystem.import');
			Route::post('/import_file', 'WarehouseSystem\ImportController@import_file')->name('warehousesystem.import.import_file');
			Route::prefix('/detail')->group(function()
			{
				Route::get('/', 'WarehouseSystem\ImportController@detail')->name('warehousesystem.import.detail');
				Route::post('/add-pallet', 'WarehouseSystem\ImportController@add_pallet')->name('warehousesystem.import.detail.add_pallet');
				Route::get('/check', 'WarehouseSystem\ImportController@check')->name('warehousesystem.import.check');
				Route::get('/get_list', 'WarehouseSystem\ImportController@get_list')->name('warehousesystem.import.get_list');
				Route::get('/destroy', 'WarehouseSystem\ImportController@destroy')->name('warehousesystem.import.destroy');
				Route::get('/cancel', 'WarehouseSystem\ImportController@cancel')->name('warehousesystem.import.cancel');
				Route::post('/add-stock', 'WarehouseSystem\ImportController@add_stock')->name('warehousesystem.import.detail.add_stock');
			});
		});
		Route::prefix('/Stock')->group(function()
		{
			Route::get('/', 'MasterData\MasterWarehouseController@location')->name('warehousesystem.import.location');
			Route::get('/list', 'WarehouseSystem\ImportController@inventory')->name('warehousesystem.import.detail.inventory');

		});

		Route::prefix('/retype')->group(function()
		{
			Route::get('/', 'WarehouseSystem\ImportController@retype')->name('warehousesystem.retype');
			Route::post('/add', 'WarehouseSystem\ImportController@retype_add')->name('warehousesystem.retype.add');
			Route::get('/check-infor', 'WarehouseSystem\ImportController@check_infor')->name('warehousesystem.check_infor');
		});

		Route::prefix('/export')->group(function()
		{
			Route::get('/', 'WarehouseSystem\ExportController@export')->name('warehousesystem.export');
			Route::get('/add', 'WarehouseSystem\ExportController@export_add')->name('warehousesystem.export.add');
			Route::get('/cancel', 'WarehouseSystem\ExportController@cancel')->name('warehousesystem.export.cancel');
			Route::get('/accept', 'WarehouseSystem\ExportController@accept')->name('warehousesystem.export.accept');
			Route::get('/success', 'WarehouseSystem\ExportController@success')->name('warehousesystem.export.success');
			Route::prefix('/detail')->group(function()
			{
				Route::get('/', 'WarehouseSystem\ExportController@detail')->name('warehousesystem.export.detail');
				Route::get('/ex', 'WarehouseSystem\ExportController@ex')->name('warehousesystem.export.detail.ex');
			});
		});
		Route::prefix('/Transfer')->group(function()
		{
			Route::get('/', 'WarehouseSystem\TransferController@transfer')->name('warehousesystem.transfer');
			Route::get('/add', 'WarehouseSystem\TransferController@add_transfer')->name('warehousesystem.transfer.add_transfer');
		});

		Route::prefix('/Update-Location')->group(function()
		{
			Route::get('/', 'WarehouseSystem\TransferController@get_list_update_location')->name('warehousesystem.update_location');
			Route::get('/get_list_box', 'WarehouseSystem\TransferController@get_list_box')->name('warehousesystem.update_location.get_list_box');
			Route::get('/get_list_pallet', 'WarehouseSystem\TransferController@get_list_pallet')->name('warehousesystem.update_location.get_list_pallet');
			Route::post('/update_location', 'WarehouseSystem\TransferController@update_location')->name('warehousesystem.update_location.update_location');
		});

		Route::prefix('/inventory')->group(function()
		{
			Route::get('/', 'WarehouseSystem\InventoryController@inventory')->name('warehousesystem.inventory');
			
			Route::get('/detail', 'WarehouseSystem\InventoryController@detail')->name('warehousesystem.inventory.detail');
			Route::get('/detail_inven', 'WarehouseSystem\InventoryController@detail_inven')->name('warehousesystem.inventory.detail_inven');
			Route::post('/add', 'WarehouseSystem\InventoryController@inventory_add')->name('warehousesystem.inventory.add');
			Route::get('/update', 'WarehouseSystem\InventoryController@inventory_update')->name('warehousesystem.inventory.update');
			Route::get('/success', 'WarehouseSystem\InventoryController@success')->name('warehousesystem.inventory.success');
			Route::get('/cancel', 'WarehouseSystem\InventoryController@cancel')->name('warehousesystem.inventory.cancel');
		});

		Route::prefix('/productivity')->group(function()
		{
			Route::get('/', 'WarehouseSystem\ProductivityController@productivity')->name('warehousesystem.productivity');
			Route::post('/import_file', 'WarehouseSystem\ProductivityController@import_file')->name('warehousesystem.productivity.import_file');
			Route::get('/show', 'WarehouseSystem\ProductivityController@show')->name('warehousesystem.productivity.show');
			Route::get('/export_file', 'WarehouseSystem\ProductivityController@export_file')->name('warehousesystem.productivity.export_file');
			Route::post('/update', 'WarehouseSystem\ProductivityController@update')->name('warehousesystem.productivity.update');
		});

		Route::prefix('/report')->group(function()
		{
			Route::get('/', 'WarehouseSystem\ReportController@index')->name('warehousesystem.report');
			Route::get('/export_file', 'WarehouseSystem\ReportController@export_file')->name('warehousesystem.report.export_file');
		});
	});
	Route::prefix('/print_label')->group(function()
	{
		Route::get('/', 'WarehouseSystem\ImportController@print_label')->name('warehousesystem.import.print_label');
		Route::get('/detail-box', 'WarehouseSystem\ImportController@detail_box')->name('warehousesystem.import.print_label.detail_box');
	});
	// Acount
	Route::prefix('/setting-account')->middleware('role:view_master')->group(function()
	{
		// User
		Route::prefix('/user')->group(function()
		{
			Route::get('/', 'Account\AccountController@index')->name('account');
			Route::get('/show', 'Account\AccountController@show')->name('account.show')->middleware(['role:create_master', 'role:update_master']);
			Route::post('/add-or-update', 'Account\AccountController@add_or_update')->name('account.addOrUpdate')->middleware(['role:create_master', 'role:update_master']);
			Route::post('/reset-password', 'Account\AccountController@reset_password')->name('account.resetPassword');
			Route::get('/check-password', 'Account\AccountController@check_password')->name('account.checkPassword');
			Route::get('/reset-my-password', 'Account\AccountController@reset_my_password')->name('account.resetMyPassword');
			Route::post('/reset-new-my-password', 'Account\AccountController@reset_new_my_password')->name('account.resetNewMyPassword');
			Route::get('/destroy', 'Account\AccountController@destroy')->name('account.destroy')->middleware(['role:create_master', 'role:delete_master']);
		});

		// Role
		Route::prefix('/role')->group(function()
		{
			Route::get('/', 'Account\AccountController@index_role')->name('account.role');
			Route::get('/show', 'Account\AccountController@show_role')->name('account.role.show');
			Route::post('/add-or-update', 'Account\AccountController@add_or_update')->name('account.role.addOrUpdate');
			Route::get('/destroy', 'Account\AccountController@destroy')->name('account.role.destroy');
		});
		
	});
































});


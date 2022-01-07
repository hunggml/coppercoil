<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\WarehouseSystem\ExportDetail;

class DashboardController extends Controller
{


    

    public function get_api_chart()
    {
    	$start = microtime(true);
		$dataImport               = array();
		$dataExport               = array();
		$dataInventories          = array();

		$lastMonth                = Carbon::now()->startOfMonth()->toDateTimeString();
		$now                      = Carbon::now()->toDateTimeString();
		$lastFindMonth 		      = Carbon::now()->sub(6, 'month')->startOfMonth()->toDateTimeString();
		// dd($run);
		
		// $imports                  = $this->import->get_all_list_import_materials();
		// $exports                  = $this->export->get_all_list_export_materials();

		// $imports                  = $this->import->get_all_list_import_materials_use_month((object)[
		// 	'from'	=> $lastFindMonth,
		// 	'to'	=> $now	
		// ]);
		$imports = ImportDetail::where('IsDelete', 0)
		->where('Status',1)
		->where('Type','<>',3)
		->where('Time_Import', '>=', $lastFindMonth)
		->where('Time_Import', '<=', $now)
		->count();

		// $exports                  = $this->export->get_all_list_export_materials_use_month((object)[
		// 	'from'	=> $lastFindMonth,
		// 	'to'	=> $now	
		// ]);
		$exports = ExportDetail::where('IsDelete', 0)
		->where('Status',1)
		->where('Type','<>',2)
		->where('Time_Created', '>=', $lastFindMonth)
		->where('Time_Created', '<=', $now)
		->count();
		
		$import                   = $imports;
									// ->where('Time_Created' , '>=', $lastMonth)
									// ->where('Time_Created', '<=', $now)
									// ->sum('Quantity');

		$dataImport['t'.'6']      = intval($import);
		
		$export                   = $exports;
									// ->whereIn('Status', [3, 5])
									// ->where('Time_Created' , '>=', $lastMonth)
									// ->where('Time_Created', '<=', $now)
									// ->sum('Quantity');
		
		$dataExport['t'.'6']      = intval($export);
		
		$dataInventories['t'.'6'] = intval($import) - intval($export);

    	for ($i = 1; $i <= 6 ; $i++) 
    	{ 
			$now     = Carbon::now()->sub($i, 'month');
			
			$last    = $now->startOfMonth()->toDateTimeString();
			$endLast = $now->endOfMonth()->toDateTimeString();
			
			$import  = ImportDetail::where('IsDelete', 0)
			->where('Status',1)
			->where('Type','<>',3)
			->where('Time_Import', '>=', $last)
			->where('Time_Import', '<=', $endLast)
			->count();

			// $imports->whereBetween('Time_Created', [$last, $endLast])->sum('Quantity');
			
			// $export  = $exports->whereIn('Status', [3])
			// ->whereBetween('Time_Created', [$last, $endLast])->sum('Quantity');
			$export = ExportDetail::where('IsDelete', 0)
			->where('Status',1)
			->where('Type','<>',2)
			->where('Time_Created', '>=', $last)
			->where('Time_Created', '<=', $endLast)
			->count();
			
			$dataImport['t'.(6 - $i)]      = intval($import);
			$dataExport['t'.(6 - $i)]      = intval($export);
			$dataInventories['t'.(6 - $i)] = intval($import) - intval($export);
    	}

    	$end = microtime(true);
    	// dd($end - $start);
    	// dd('ok');

    	return response()->json([
    		'success' => true,
    		'data'	  => [
				'import'    => $dataImport,
				'export'    => $dataExport,
				'inventory' => $dataInventories,
    		]
    	]);
    }


}

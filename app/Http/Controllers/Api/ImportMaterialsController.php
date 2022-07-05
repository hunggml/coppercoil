<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\WarehouseSystem\ExportDetail;
use App\Models\WarehouseSystem\TransferMaterials;
use App\Models\MasterData\MasterWarehouseDetail;
use App\Models\MasterData\MasterWarehouse;
use App\Models\MasterData\MasterMaterials;
use App\Models\MasterData\GroupMaterials;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ImportMaterialsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:api');
	}

	public function get_data_input_pallet(Request $request)
	{
		$command_import = ImportDetail::where('IsDelete', 0)
			->where('Pallet_ID', $request->Pallet_ID)
			->where('Status', 0)
			->select('ID', 'Quantity', 'Materials_ID', 'Pallet_ID', 'Warehouse_Detail_ID', 'Box_ID')
			->get();

		if ($command_import->count() == 0) {
			return response()->json([
				'success' 	=> false,
				'data' 		=> ['message' => __('Pallet_ID') . ' ' . __('In the completed or canceled import command')]
			], 400);
		} else {
			$val = [];
			$val['Pallet_ID'] 		= $command_import[0]->Pallet_ID;
			$val['Materials'] 		= $command_import[0]->materials ? $command_import[0]->materials->Symbols : '';
			$val['Spec']			= $command_import[0]->materials ? $command_import[0]->materials->Spec   : '';
			$val['Quantity'] 		= number_format(collect($command_import)->sum('Quantity'), 2, '.', '');
			$val['Count'] 			= count($command_import);
			$val['List'] 			= $command_import;

			return response()->json([
				'success' => true,
				'data' => $val
			], 200);
		}
	}

	public function import_packing_list(Request $request)
	{
		if (empty($request->Pallet_ID)) {
			return response()->json([
				'success' 	=> false,
				'data' 		=> ['message' => __('Dont Scan') . ' ' . __("Pallet")]
			], 400);
		}

		if (empty($request->Warehouse_Detail_ID)) {
			return response()->json([
				'success' 	=> false,
				'data' 		=> ['message' => __('Location') . ' ' . __("Can't be empty")]
			], 400);
		}

		$data = ImportDetail::where('IsDelete', 0)
			->where('Pallet_ID', $request->Pallet_ID)
			->where('Status', 0) // 0 là chưa nhập kho
			->with('materials')
			->get();
		// dd($data);

		$location = MasterWarehouseDetail::where('IsDelete', 0)
			->where('Symbols', $request->Warehouse_Detail_ID)
			->first();
		if (!$location) {
			return response()->json([
				'success' 	=> false,
				'data' 		=> ['message' => __('Location') . ' ' . __('Does Not Exist')]
			], 400);
		}

		$warehouse = MasterWarehouse::where('IsDelete', 0)
			->where('ID', $location->Warehouse_ID)
			->first();


		if (count($data) > 0) {
			foreach ($data as $value) {
				$mat = MasterMaterials::where('IsDelete', 0)
					->where('ID', $value->materials->ID)
					->first();

				$groupMat_warehouse = GroupMaterials::where('IsDelete', 0)
					->where('Group_ID', $warehouse->Group_Materials_ID)
					->where('Materials_ID', $mat->ID)
					->first();

				$groupMat_location = GroupMaterials::where('IsDelete', 0)
					->where('Group_ID', $location->Group_Materials_ID)
					->where('Materials_ID', $mat->ID)
					->first();

				if (!is_null($warehouse->Group_Materials_ID)) {
					if (!$groupMat_warehouse) {
						return response()->json([
							'success' 	=> false,
							'data' 		=> ['message' => __('Materials') . ' ' . $value->materials->Symbols . ' ' . __("Can't be import in warehouse") . ' ' . $warehouse->Symbols]
						], 400);
					}
				}

				if (!is_null($location->Group_Materials_ID)) {
					if (!$groupMat_location) {
						return response()->json([
							'success' 	=> false,
							'data' 		=> ['message' => __('Materials') . ' ' . $value->materials->Symbols . ' ' . __("Can't be import in location") . ' ' . $location->Symbols]
						], 400);
					}
				}

				$data =  ImportDetail::where('IsDelete', 0)
					->where('ID', $value->ID)
					->update([
						'User_Updated'     		=> Auth::user()->id,
						'Inventory'       		=> $value->Quantity,
						'Time_Import'      		=> Carbon::now(),
						'Warehouse_Detail_ID'   => $location->ID,
						'Status'          		=> 1 // đã nhập kho
					]);
			}

			return response()->json([
				'success' => true,
				'data' => ['message' => __('Import') . ' ' . __('Warehouse') . ' ' . __('Success')]
			], 200);
		} else {
			return response()->json([
				'success' => false,
				'data' => ['message' => __('Import') . ' ' . __('Warehouse') . ' ' . __('Fail')]
			], 400);
		}
	}

	public function get_data_update_location(Request $request)
	{
		// box
		if ($request->Type == 1) {
			$label = $request->Box_ID;
			$arr_label = explode('[1D]', $label);
			if (count($arr_label) > 12) {
				$label_1 = $arr_label[12];
				$label_2 = str_replace('Z', '', $label_1);
				$label_3 = str_replace('[1E][04]', '', $label_2);

				if ($label_3 != '') {
					$data1 = ImportDetail::where('IsDelete', 0)
						->where('Inventory', '>', 0) // tồn kho
						->where('Box_ID', $label_3)
						->orderBy('ID', 'desc')
						->first();
					if ($data1) {
						return response()->json([
							'success' => true,
							'data'	  => [
								'Box_ID'	=> $label_3,
								'Quantity'	=> floatval($data1->Quantity),
								'Materials'	=> $data1->materials ? $data1->materials->Symbols : '',
								'Spec'		=> $data1->materials ? $data1->materials->Spec : '',
								'Location'	=> $data1->location ? $data1->location->Symbols : '',
							]
						], 200);
					} else {
						return response()->json([
							'success' => false,
							'data'	  => ['message' => __('Box') . ' ' . __('Dont') . ' ' . __('Stock')]
						], 400);
					}
				} else {
					return response()->json([
						'success' => false,
						'data'	  => ['message' => __('Box') . ' ' . __('Does Not Exist')]
					], 400);
				}
			} else {
				return response()->json([
					'success' => false,
					'data'	  => ['message' => __('Box') . ' ' . __('Does Not Exist')]
				], 400);
			}
		}
		// pallet
		else if ($request->Type == 2) {
			$data =  ImportDetail::where('IsDelete', 0)
				->where('Pallet_ID', $request->Pallet_ID)
				->where('Inventory', '>', 0) // lớn hơn 0 là tồn kho
				->select('ID', 'Quantity', 'Materials_ID', 'Pallet_ID', 'Warehouse_Detail_ID', 'Box_ID')
				->get();

			if (count($data) > 0) {
				$val = [];
				$val['Pallet_ID'] 	= $data[0]->Pallet_ID;
				$val['Spec'] 		= $data[0]->materials ? $data[0]->materials->Spec : '';
				$val['Materials'] 	= $data[0]->materials ? $data[0]->materials->Symbols : '';
				$val['Location'] 	= $data[0]->location ? $data[0]->location->Symbols : '';
				$val['Quantity'] 	= number_format(collect($data)->sum('Quantity'), 2, '.', '');
				$val['Count'] 		= count($data);
				$val['List'] 		= $data;

				return response()->json([
					'success' 	=> true,
					'data' 		=> $val
				], 200);
			} else {
				return response()->json([
					'success' 	=> false,
					'data' 		=> ['message' => __('Pallet') . ' ' . __("Don't") . ' ' . __('Stock')]
				], 400);
			}
		}
	}

	public function update_location(Request $request)
	{
		if (empty($request->Warehouse_Detail_ID)) {
			return response()->json([
				'success' => false,
				'data' => ['message' => __('Location') . ' ' . __("Can't be empty")]
			], 400);
		}
		if (empty($request->Type)) {
			return response()->json([
				'success' => false,
				'data' => ['message' => __('Type') . ' ' . __("Can't be empty")]
			], 400);
		}

		$location = MasterWarehouseDetail::where('IsDelete', 0)
			->where('Symbols', $request->Warehouse_Detail_ID)
			->first();

		if (!$location) {
			return response()->json([
				'success' => false,
				'data' => ['message' => __('Location') . ' ' . __('Does Not Exist')]
			], 400);
		}
		// box
		if ($request->Type == 1) {
			if (empty($request->Box_ID)) {
				return response()->json([
					'success' => false,
					'data' => ['message' => __('Dont Scan') . ' ' . __("Box")]
				], 400);
			}

			$data =  ImportDetail::where('IsDelete', 0)
				->where('Box_ID', $request->Box_ID)
				->where('Inventory', '>', 0) // tồn kho
				->orderBy('ID', 'desc')
				->first();
			if (!$data) {
				// return response()->json('false');
				return response()->json([
					'success' => false,
					'data' => ['message' => __('Box') . ' ' . __("Don't") . ' ' . __('Stock')]
				], 400);
			}
			// return response()->json('true');

			$groupMat_location = GroupMaterials::where('IsDelete', 0)
				->where('Group_ID', $location->Group_Materials_ID)
				->where('Materials_ID', $data->Materials_ID)
				->first();

			if (!is_null($location->Group_Materials_ID)) {
				if (!$groupMat_location) {
					return response()->json([
						'success' 	=> false,
						'data' 		=> ['message' => __('Materials') . ' ' . $data->materials->Symbols . ' ' . __("Can't be import in location") . ' ' . $location->Symbols]
					], 400);
				}
			}

			$old_location = MasterWarehouseDetail::where('IsDelete', 0)
				->where('ID', $data->Warehouse_Detail_ID)
				->first();

			if ($old_location->Warehouse_ID != $location->Warehouse_ID) {
				return response()->json([
					'success' => false,
					'data' => ['message' => __('Location') . ' ' . __("Not in the current inventory")]
				], 400);
			}

			$arr1 = [
				'Export_ID' 			=> '',
				'Pallet_ID' 			=> '',
				'Box_ID'    			=> $data->Box_ID,
				'Materials_ID' 			=> $data->Materials_ID,
				'Warehouse_Detail_ID' 	=> $data->Warehouse_Detail_ID,
				'Quantity'  			=> $data->Quantity,
				'Status'    			=> 1, // xuất
				'Type'      			=> 2, // xuất cập nhật vị trí
				'Time_Export' 			=> Carbon::now(),
				'User_Created'     		=> Auth::user()->id,
				'User_Updated'     		=> Auth::user()->id,
				'IsDelete'         		=> 0
			];
			ExportDetail::Create($arr1);

			$data->update([
				'Inventory' => 0, // không tồn kho
				'User_Updated' => Auth::user()->id
			]);

			$arr = [
				'Materials_ID'        => $data->Materials_ID,
				'Box_ID'              => $data->Box_ID,
				'Case_No'             => $data->Case_No,
				'Lot_No'              => $data->Lot_No,
				'Time_Import'         => $data->Time_Import,
				'Pallet_ID'           => '',
				'Quantity'            => $data->Quantity,
				'Inventory'           => $data->Quantity,
				'Warehouse_Detail_ID' => $location->ID,
				'Status'              => 1, // đã nhập kho
				'Type'                => 3, // nhập cập nhật vị trí
				'User_Created'        => Auth::user()->id,
				'User_Updated'        => Auth::user()->id,
				'IsDelete'            => 0
			];
			ImportDetail::create($arr);

			$dataSave = ([
				'Export_ID'        			=> '',
				'Export_Detail_ID' 			=> '',
				'Pallet_ID' 				=> '',
				'Box_ID' 					=> $data->Box_ID,
				'Materials_ID' 				=> $data->Materials_ID,
				'Warehouse_Detail_ID_Go' 	=> $data->Warehouse_Detail_ID,
				'Warehouse_Detail_ID_To' 	=> $location->ID,
				'Quantity' 					=> $data->Quantity,
				'Status' 					=> 2, // chuyển kho cho cập nhật vị trí
				'User_Created'     			=> Auth::user()->id,
				'User_Updated'     			=> Auth::user()->id,
				'IsDelete'         			=> 0
			]);

			TransferMaterials::create($dataSave);

			return response()->json([
				'success' => true,
				'data' => ['message' => __('Update') . ' ' . __('Location') . ' ' . __('Success')]
			], 200);
		}
		// pallet
		else if ($request->Type == 2) {
			if (empty($request->Pallet_ID)) {
				return response()->json([
					'success' => false,
					'data' => ['message' => __('Dont Scan') . ' ' . __("Pallet")]
				], 400);
			}

			$data =  ImportDetail::where('IsDelete', 0)
				->where('Pallet_ID', $request->Pallet_ID)
				->where('Inventory', '>', 0) // tồn kho
				->orderBy('ID', 'desc')
				->get();
			if (count($data) <= 0) {
				// dd('false');
				return response()->json([
					'success' => false,
					'data' => ['message' => __('Pallet') . ' ' . __("Don't") . ' ' . __('Stock')]
				], 400);
			}
			// dd('true');

			$old_location = MasterWarehouseDetail::where('IsDelete', 0)
				->where('ID', $data[0]->Warehouse_Detail_ID)
				->first();

			// từ chi tiết vị trí -> lấy được kho -> so sánh type của kho ( cùng type là cùng kho)
			if ($old_location->Warehouse_ID != $location->Warehouse_ID) {
				return response()->json([
					'success' => false,
					'data' => ['message' => __('Location') . ' ' . __("Not in the current inventory")]
				], 400);
			}

			foreach ($data as $value1) {
				$groupMat_location = GroupMaterials::where('IsDelete', 0)
					->where('Group_ID', $location->Group_Materials_ID)
					->where('Materials_ID', $value1->Materials_ID)
					->first();

				if (!is_null($location->Group_Materials_ID)) {
					if (!$groupMat_location) {
						return response()->json([
							'success' 	=> false,
							'data' 		=> ['message' => __('Materials') . ' ' . $value1->materials->Symbols . ' ' . __("Can't be import in location") . ' ' . $location->Symbols]
						], 400);
					}
				}

				$arr1 = [
					'Export_ID' 			=> '',
					'Box_ID'    			=> $value1->Box_ID,
					'Pallet_ID' 			=> $value1->Pallet_ID,
					'Materials_ID' 			=> $value1->Materials_ID,
					'Warehouse_Detail_ID' 	=> $value1->Warehouse_Detail_ID,
					'Quantity'  			=> $value1->Quantity,
					'Status'    			=> 1, // đã xuất
					'Type'      			=> 2, // xuất cập nhật vị trí
					'Time_Export' 			=> Carbon::now(),
					'User_Created'    		=> Auth::user()->id,
					'User_Updated'     		=> Auth::user()->id,
					'IsDelete'         		=> 0
				];
				ExportDetail::Create($arr1);

				$value1->update([
					'Inventory' => 0, // không tồn kho
					'User_Updated' => Auth::user()->id,
				]);

				$arr = [
					'Materials_ID'        => $value1->Materials_ID,
					'Box_ID'              => $value1->Box_ID,
					'Pallet_ID'           => $value1->Pallet_ID,
					'Case_No'             => $value1->Case_No,
					'Lot_No'              => $value1->Lot_No,
					'Time_Import'         => $value1->Time_Import,
					'Quantity'            => $value1->Quantity,
					'Inventory'           => $value1->Quantity,
					'Warehouse_Detail_ID' => $location->ID,
					'Status'              => 1, // đã nhập kho
					'Type'                => 3, // nhập cập nhật vị trí
					'User_Created'        => Auth::user()->id,
					'User_Updated'        => Auth::user()->id,
					'IsDelete'            => 0
				];
				ImportDetail::create($arr);

				$dataSave = ([
					'Export_ID'        			=> '',
					'Export_Detail_ID' 			=> '',
					'Pallet_ID' 				=> $value1->Pallet_ID,
					'Box_ID' 					=> $value1->Box_ID,
					'Materials_ID' 				=> $value1->Materials_ID,
					'Warehouse_Detail_ID_Go' 	=> $value1->Warehouse_Detail_ID,
					'Warehouse_Detail_ID_To' 	=> $location->ID,
					'Quantity' 					=> $value1->Quantity,
					'Status' 					=> 2, // chuyển cho cho cập nhật
					'User_Created'     			=> Auth::user()->id,
					'User_Updated'     			=> Auth::user()->id,
					'IsDelete'        			=> 0
				]);

				TransferMaterials::create($dataSave);
			}

			return response()->json([
				'success' => true,
				'data' => ['message' => __('Update') . ' ' . __('Location') . ' ' . __('Success')]
			], 200);
		}
	}

	// public  function decryption_box(Request $request)
	// {
	// 	$label = $request->Box_ID;
	// 	$arr_label = explode('[1D]',$label);
	// 	if(count($arr_label) >12)
	// 	{
	// 		$label_1 = $arr_label[12];
	// 		$label_2 = str_replace('Z','',$label_1);
	// 		$label_3 = str_replace('[1E][04]','',$label_2);

	// 		if($label_3 != '')
	// 		{
	// 			$data1 = ImportDetail::where('IsDelete',0)
	// 								->where('Box_ID',$label_3)
	// 								->where('Status','>',0)
	// 								->orderBy('ID','desc')
	// 								->first();
	// 			if($data1)
	// 			{
	// 				if($data1->Inventory == 0)
	// 				{
	// 					return response()->json([
	// 						'success' => true,
	// 						'data'	  => [
	// 							'Box_ID'	=> $label_3,
	// 							'Quantity'	=> floatval($data1->Quantity),
	// 							'Location'	=> $data1->location ? $data1->location->ID : '',
	// 						]
	// 					],200);
	// 				}
	// 				else
	// 				{
	// 					return response()->json([
	// 						'success' => false,
	// 						'data'	  => ['message' => __('Box').' '.__('Chưa Xuất')]
	// 					],400);
	// 				}
	// 			}
	// 			else
	// 			{
	// 				return response()->json([
	// 					'success' => false,
	// 					'data'	  => ['message' => __('Box').' '.__('Does Not Exist')]
	// 				],400);
	// 			}
	// 		}
	// 		else
	// 		{
	// 			return response()->json([
	// 				'success' => false,
	// 				'data'	  => ['message' => __('Box').' '.__('Does Not Exist')]
	// 			],400);
	// 		}
	// 	}
	// 	else
	// 	{
	// 		return response()->json([
	// 			'success' => false,
	// 			'data'	  => ['message' => __('Box').' '.__('Does Not Exist')]
	// 		],400);
	// 	}
	// }

	public function retype_add(Request $request)
	{
		$detail = $request->detail;
		$dem = 0;
		$location = MasterWarehouseDetail::where('IsDelete', 0)
			->where('Symbols', $request->location)
			->first();
		$arr1 = [];
		if (empty($detail)) {
			return response()->json([
				'success' => false,
				'data' => ['message' => __('Dont Scan') . ' ' . __("Box")]
			], 400);
		}

		if (empty($request->location)) {
			return response()->json([
				'success' => false,
				'data' => ['message' => __('Location') . ' ' . __("Can't be empty")]
			], 400);
		}

		if (!$location) {
			return response()->json([
				'success' => false,
				'data' => ['message' => __('Location') . ' ' . __('Does Not Exist')]
			], 400);
		}

		foreach ($detail as $val) {
			// dd('1');
			$data =  ImportDetail::where('IsDelete', 0)
				->where('Box_ID', $val['Box_ID'])
				->where('Status', '>', 0) // đã nhập kho
				->orderBy('ID', 'desc')
				->first();
			// return response()->json($data);
			if ($data) {
				if ($data->Inventory != 0) // còn tồn
				{
					return response()->json([
						'success' => false,
						'data'	  => ['message' => __('Box') . ' ' . __('Chưa Xuất')]
					], 400);
				}
			} else {
				return response()->json([
					'success' => false,
					'data'	  => ['message' => $val['Box_ID'] . ' ' . __('Not in command export')]
				], 400);
			}
		}

		foreach ($detail as $value) {

			$data =  ImportDetail::where('IsDelete', 0)
				->where('Box_ID', $value['Box_ID'])
				->where('Status', '>', 0) // đã nhập kho
				->where('Inventory', 0) // không còn tồn
				->orderBy('ID', 'desc')
				->first();

			$old_location = MasterWarehouseDetail::where('IsDelete', 0)
				->where('ID', $data->Warehouse_Detail_ID)
				->first();

			// từ chi tiết vị trí -> lấy được kho -> so sánh type của kho ( cùng type là cùng kho)
			if ($old_location->Warehouse_ID != $location->Warehouse_ID) {
				return response()->json([
					'success' => false,
					'data' => ['message' => $value['Box_ID'] . ' ' . __('Retype') . ' ' . __("Not in warehouse export")]
				], 400);
			}

			$arr = [
				'Materials_ID'     => $data->Materials_ID,
				'Box_ID'           => $value['Box_ID'],
				'Case_No'          => $data->Case_No,
				'Lot_No'           => $data->Lot_No,
				'Time_Import'      => $data->Time_Import,
				'Quantity'         => $value['Quantity'],
				'Inventory'        => $value['Quantity'],
				'Warehouse_Detail_ID' => $location->ID,
				'Status'           => 1, // đã nhập kho
				'Type'             => 1, // nhập lại
				'Time_Created'	   => now(),
				'Time_Updated'	   => now(),
				'User_Created'     => Auth::user()->id,
				'User_Updated'     => Auth::user()->id,
				'IsDelete'         => 0
			];

			array_push($arr1, $arr);
			$dem++;
		}
		foreach (array_chunk($arr1, 50) as $value3) {

			ImportDetail::insert($value3);
		}


		if ($dem > 0) {
			return response()->json([
				'success' => true,
				'data'	  => ['message' => __('Retype') . ' ' . __('Box') . ' ' . __('Success')]
			], 200);
		} else {
			return response()->json([
				'success' => false,
				'data'	  => ['message' => __('Retype') . ' ' . __('Box') . ' ' . __('Fail')]
			], 400);
		}
	}
}

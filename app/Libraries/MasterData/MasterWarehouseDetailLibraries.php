<?php

namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterWarehouseDetail;
use App\Models\PrintLabel\Label;
use Carbon\Carbon;
use Validator;
use Auth;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\WarehouseSystem\TransferMaterials;
use App\Models\WarehouseSystem\ExportDetail;
use App\Models\WarehouseSystem\CommandImport;
 
/**
 * 
 */
class MasterWarehouseDetailLibraries
{
	public function get_all_list_warehouse_detail($request)
	{
		// $comm = CommandImport::where('ID',$request->ID)->first();
        // dd($comm);
		$data = MasterWarehouseDetail::where('IsDelete', 0)->get();

		return $data;
	}

	public function control_led_one_position($request)
	{
		MasterWarehouseDetail::where('ID', $request->ID)->update([
			'R'          => $request->R,
			'G'          => $request->G,
			'B'          => $request->B,
			'Status_Led' => $request->Status_Led,
		]);

		return __('Success');
	}

	public function filter_led($request)
	{
		$mac 	 = $request->MAC;

		$data = MasterWarehouseDetail::where('IsDelete', 0)
		->where('MAC', $mac)
		->select('Position_Led', 'Status_Led', 'R', 'G', 'B')
		->get();

		return $data;
	}

	public function filter_floor($request)
	{
		$floor = $request->Floor;

		$data = MasterWarehouseDetail::where('IsDelete', 0)
		->where('Floor', $floor)
		->select('ID')
		->get();

		return $data;
	}


	public function filter_in_id($request)
	{
		$data = MasterWarehouseDetail::where('IsDelete', 0)
		->whereIn('ID', $request)
		->with([
			'inventory'
		])
		->get();

		return $data;
	}

	public function filter_in_symbols($request)
	{
		$data = MasterWarehouseDetail::where('IsDelete', 0)
		->whereIn('Symbols', $request)
		->with([
			'inventory.label.materials',
			'group_materials'
		])
		->get();

		return $data;
	}

	public function filter_no_with($request)
	{
		$id      = $request->ID;
		$name    = $request->Name;
		$symbols = $request->Symbols;
		$mac 	 = $request->MAC;

		$datas = MasterWarehouseDetail::where('IsDelete', 0)
		->when($id, function($q, $id)
		{
			return $q->where('ID', $id);
		})
		->when($name, function($q, $name)
		{
			return $q->where('Name', $name);
		})
		->when($symbols, function($q, $symbols)
		{
			return $q->where('Symbols', $symbols);
		})
		->when($mac, function($q, $mac)
		{
			return $q->where('MAC', $mac);
		})
		->get();

		return $datas;
	}

	public function filter_detail($request)
	{
		$id      = $request->ID;
		$name    = $request->Name;
		$symbols = $request->Symbols;
		$mac 	 = $request->MAC;

		if (!$id && !$name && !$symbols && !$mac) 
		{
			return [];
		}

		$datas = MasterWarehouseDetail::where('IsDelete', 0)
		->when($id, function($q, $id)
		{
			return $q->where('ID', $id);
		})
		->when($name, function($q, $name)
		{
			return $q->where('Name', $name);
		})
		->when($symbols, function($q, $symbols)
		{
			return $q->where('Symbols', $symbols);
		})
		->when($mac, function($q, $mac)
		{
			return $q->where('MAC', $mac);
		})
		->with([
			'inventory2:ID,Inventory,Label_ID,User_Created,Time_Created,User_Updated,Time_Updated,Warehouse_Detail_ID',
			'inventory2.label.materials:ID,Name,Symbols,Unit_ID,Model',
			'inventory2.label.materials.unit',
			'inventory2.user_created:id,name,username',
			'inventory2.user_updated:id,name,username',
			// 'inventory_null:ID,Inventory,Label_ID,User_Created,Time_Created,User_Updated,Time_Updated,Warehouse_Detail_ID',
			// 'inventory_null.label.materials:ID,Name,Symbols,Model',
			// 'inventory_null.user_created:id,name,username',
			// 'inventory_null.user_updated:id,name,username',
		])
		->get();
		foreach($datas as $value)
		{
			
				$value['sum'] = collect($value->inventory2)->sum('Inventory');
			
		}
		return $datas;
	}

	public function filter($request)
	{
		$id      = $request->ID;
		$name    = $request->Name;
		$symbols = $request->Symbols;
		$mac 	 = $request->MAC;

		$datas = MasterWarehouseDetail::where('IsDelete', 0)
		->when($id, function($q, $id)
		{
			return $q->where('ID', $id);
		})
		->when($name, function($q, $name)
		{
			return $q->where('Name', $name);
		})
		->when($symbols, function($q, $symbols)
		{
			return $q->where('Symbols', $symbols);
		})
		->when($mac, function($q, $mac)
		{
			return $q->where('MAC', $mac);
		})
		->with([
			'inventory.materials',
			'group_materials',
			// 'inventory.supplier',
			// 'inventory:ID,Inventory,User_Created,Time_Created,User_Updated,Time_Updated,Warehouse_Detail_ID',
			// 'inventory_null:ID,Inventory,Label_ID,User_Created,Time_Created,User_Updated,Time_Updated,Warehouse_Detail_ID',
			// 'inventory_null.label.materials:ID,Name,Symbols,Model',
			'inventory_null.user_created:id,name,username',
			'inventory_null',
			'inventory.user_created:id,name,username',
			'inventory.user_updated:id,name,username',
		])
		->withCount([
			'inventory_null',
			'inventory1'
		])
		->get();

		return $datas;

		// dd($datas[260]);
		// $dataAll = collect(array());
		// foreach ($datas as $key => $value) 
		// {
		// 	// dd( $value->inventory->pluck('Label_ID')->toArray());

		// 	// dd($value);
		// 	// var_dump($value->inventory->pluck('Label_ID')->toArray(), $key);
		// 	if ($value->inventory->count() != 0) 
		// 	{
		// 		foreach ($value->inventory as $key1 => $inven) 
		// 		{
		// 			$inven->label = Label::where('IsDelete', 0)->where('ID', $inven->Label_ID)->with(['materials.unit'])->first();

		// 			$dataAll->push($value);
		// 		}
		// 	} else
		// 	{
		// 		$dataAll->push($value);
		// 	}
			
		// 	// $value->with([
		// 	// 	'inventory:ID,Inventory,Label_ID,User_Created,Time_Created,User_Updated,Time_Updated,Warehouse_Detail_ID',
		// 	// 	// 'inventory.label.materials:ID,Name,Symbols,Unit_ID,Model',
		// 	// 	// 'inventory.label.materials.unit',
		// 	// 	'inventory.user_created:id,name,username',
		// 	// 	'inventory.user_updated:id,name,username',
		// 	// 	'inventory_null:ID,Inventory,Label_ID,User_Created,Time_Created,User_Updated,Time_Updated,Warehouse_Detail_ID',
		// 	// 	// 'inventory_null.label.materials:ID,Name,Symbols,Model',
		// 	// 	'inventory_null.user_created:id,name,username',
		// 	// 	'inventory_null.user_updated:id,name,username',
		// 	// ])->get();
		// }
		// dd($datas[260]);
		// dd($dataAll[260]);
		return $dataAll;
	}
	public function filter1($request)
	{
		$id      = $request->ID;
		$name    = $request->Name;
		$symbols = $request->Symbols;
		$mac 	 = $request->MAC;
		
		$datas = MasterWarehouseDetail::where('IsDelete', 0)
		->when($id, function($q, $id)
		{
			return $q->where('ID', $id);
		})
		->when($name, function($q, $name)
		{
			return $q->where('Name', $name);
		})
		->when($symbols, function($q, $symbols)
		{
			return $q->where('Symbols', $symbols);
		})
		->when($mac, function($q, $mac)
		{
			return $q->where('MAC', $mac);
		})
		->with([
			'inventory.materials',
			'inventory.supplier',
			'group_materials',
			'inventory',
			'inventory_null.user_created:id,name,username',
			'inventory_null',
			'inventory.user_created:id,name,username',
			'inventory.user_updated:id,name,username',
		])
		->withCount([
			'inventory_null',
			'inventory1'
		])
		->get();
		// dd($datas);
		foreach($datas as $value)
		{		
				$arr = [];
				$value1 = $value->inventory;
				foreach($value1->where('Pallet_ID','<>',null)->GroupBy('Pallet_ID') as $key =>$value2)
				{
				
					foreach($value2->GroupBy('Materials_ID') as $key =>$value3)
					{
						$value3[0]['Inventory']=number_format($value3->sum('Inventory'), 2, '.', '');
						$value3[0]['Count']= count($value3);
						if($value3[0]['Count'] > 1)
                        {
                            $value3[0]['Box_ID']= '';
                        }
						
						array_push($arr,$value3[0]);
					}
				}
				foreach($value1->where('Pallet_ID',null) as $key =>$value3)
				{
					$value3['Count']= 1;
					array_push($arr,$value3);
				}
				// dd($arr);	
			$value['inventory_nl'] = $arr;
		}
		return $datas;

	}
	public function check_detail($request)
	{
		$id         = $request->ID;
		$mac        = $request->MAC;
		$message = [
			'Position_Led.unique'       => __('Position').' '.$request->Position_Led.' '.__('Already Exists').'!',
			'Quantity_Unit.required'    => __('Quantity Unit').' '.__('Field Is Required').'!',
			'Quantity_Packing.required' => __('Quantity Packing').' '.__('Field Is Required').'!',
			'Unit_ID.required'          => __('Unit').' '.__('Field Is Required').'!',
			// 'Packing_ID.required'       => __('Packing').' '.__('Field Is Required').'!',
		];

		$validator = Validator::make($request->all(),
		[
			'Position_Led'	=> ['numeric', 
			Rule::unique('Master_Warehouse_Detail')->where(function($q) use ($id, $mac) 
	        {
	        	$q->where('ID', '!=', $id)
	        	->where('IsDelete',0)
	        	->where('MAC', $mac)
	        	->where('Position_Led', '!=', 0);
	        })],
	        'Unit_ID'    => [
				Rule::requiredIf(function() use ($request)
				{
					return $request->Quantity_Unit != '';
				}),
			],
			'Quantity_Unit'    => [
				Rule::requiredIf(function() use ($request)
				{
					return $request->Unit_ID != '';
				})
			],
			// 'Packing_ID'    => [
			// 	Rule::requiredIf(function() use ($request)
			// 	{
			// 		return $request->Quantity_Packing != '';
			// 	}),
			// ],
			'Quantity_Packing' => [
				Rule::requiredIf(function() use ($request)
				{
					return $request->Packing_ID != '';
				}),
			],
		], $message);

		return $validator->errors();
	}

	public function check_led($request)
	{

		$validator = Validator::make($request->all(),
		[
			'R' => 'numeric|min:0|max:255',
			'G' => 'numeric|min:0|max:255',
			'B' => 'numeric|min:0|max:255',
		]);

		return $validator->errors();
	}

	public function fiter_position()
	{
		$data = MasterWarehouseDetail::where('IsDelete', 0)
		->orderBy('ID', 'asc')
		->with(['inventory'])->get();

		return $data;
	}

	public function add_or_update($request)
	{
		// dd($request);
		$find = MasterWarehouseDetail::where('IsDelete', 0)
		->where('ID', $request->ID)->first();

		if (!Auth::user()->checkRole('update_master') && Auth::user()->level != 9999) 
		{
			return abort(401);
		}

		if (!$find) 
		{
			return 'No Create';
		} else
		{
			$find->MAC                = $request->MAC;
			$find->Position_Led       = $request->Position_Led;
			$find->Quantity_Unit      = $request->Quantity_Unit;
			$find->Unit_ID            = $request->Unit_ID;
			$find->Quantity_Packing   = $request->Quantity_Packing;
			$find->Min_Unit   		  = $request->Min_Unit;
			$find->Packing_ID         = $request->Packing_ID;
			$find->Accept             = $request->Accept;
			$find->Email              = $request->Email;
			$find->Email2              = $request->Email2;
			$find->Group_Materials_ID = $request->Group_Materials_ID;
			$find->Note               = $request->Note;
			$find->save();
		}

		return $find;
	}

	public function turn_on_off_led($request)
	{
		$mac          = $request->MAC;
		$position_led = $request->Position_Led;

		$find = MasterWarehouseDetail::where('IsDelete', 0)
		->when($mac, function($q, $mac)
		{
			return $q->where('MAC', $mac);
		})
		->when($position_led, function($q, $position_led)
		{
			return $q->where('Position_Led', $position_led);
		})
		->update([
			'R'          => $request->R,
			'G'          => $request->G,
			'B'          => $request->B,
			'Status_Led' => $request->Status_Led,
		]);

		return $find;
	}

	public function history_location($request)
	{
		$im = ImportDetail::where('Warehouse_Detail_ID',$request->Warehouse_Detail_ID)
							->where('Status',1)
							->where('Inventory','>',0)
							->where('Type',0)
							// ->with('materials,location')
		->get();

		$ex = ExportDetail::where('Warehouse_Detail_ID',$request->Warehouse_Detail_ID)
							->where('Transfer',1)
							// ->with('materials,location')
		->get();

		$ex_go = TransferMaterials::where('Warehouse_Detail_ID_Go',$request->Warehouse_Detail_ID)
		// ->with('materials,location_go,location_to')
		->get();


		$ex_to = TransferMaterials::where('Warehouse_Detail_ID_To',$request->Warehouse_Detail_ID)
		// ->with('materials,location_go,location_to')
		->get();
		// dd($run);
		return [
			'import_history' => $im,
			'export_history' => $ex,
			'ex_go' => $ex_go,
			'ex_to' => $ex_to
		];

	}
}
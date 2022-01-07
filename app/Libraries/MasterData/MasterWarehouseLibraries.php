<?php

namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterWarehouse;
use Carbon\Carbon;
use Validator;
use Auth;

/**
 *
 */
class MasterWarehouseLibraries
{
	public function get_all_list_warehouse()
	{
		$data = MasterWarehouse::where('IsDelete', 0)->with([
			'detail',
			'detail.inventory',
		])->get();
		// dd($data);

		return $data;
	}

	public function filter($request)
	{
		$id      = $request->ID;
		$name    = $request->Name;
		$symbols = $request->Symbols;

		$data = MasterWarehouse::where('IsDelete', 0)
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
		->with([
			'detail',
			'detail.inventory',
		])
		->get();

		return $data;
	}

	public function check_warehouse($request)
	{
		$id = $request->ID;
		$message = [
			'Symbols.unique' => $request->Symbols.' '.__('Already Exists').'!',
			'MAC.unique'     => $request->MAC.' '.__('Already Exists').'!',
		];

		Validator::make($request->all(), 
		[
			'Name'	  => 'required|max:255',
	        'Symbols' => ['required','max:255',
	        Rule::unique('Master_Warehouse')->where(function($q) use ($id) 
	        {
	        	$q->where('ID', '!=', $id)->where('IsDelete',0);
	        })],
	        'MAC' => ['max:255',
	        // Rule::unique('Master_Warehouse')->where(function($q) use ($id) 
	        // {
	        // 	$q->where('ID', '!=', $id)->where('IsDelete',0);
	        // })
	    	],
			'Quantity_Rows'    => 'required|min:1|numeric',
			'Quantity_Columns' => 'required|min:1|numeric',
	    ], $message)->validate();
	}

	public function add_or_update($request)
	{
		
		$find = MasterWarehouse::where('IsDelete', 0)
		->where('ID', $request->ID)
		->first();

		if (!$find) 
		{
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) 
			{
				abort(401);	
			}

			$find = MasterWarehouse::create([
				'Name'               => $request->Name,
				'Symbols'            => $request->Symbols,
				'Quantity_Rows'      => $request->Quantity_Rows,
				'Quantity_Columns'   => $request->Quantity_Columns,
				'MAC'                => $request->MAC,
				'Quantity_Unit'      => $request->Quantity_Unit,
				'Unit_ID'            => $request->Unit_ID,
				'Quantity_Packing'   => $request->Quantity_Packing,
				'Packing_ID'         => $request->Packing_ID,
				'Group_Materials_ID' => $request->Group_Materials_ID,
				'Floor'              => $request->Floor,
				'Accept'			 => $request->Accept,
				'Email'				 => $request->Email,
				'Email2'				 => $request->Email2,
				'Note'               => $request->Note,
				'User_Created'       => Auth::user()->id,
				'User_Updated'       => Auth::user()->id,
			]);

			$data = array();

			for ($i=1; $i <= $request->Quantity_Rows ; $i++) 
			{ 
				for ($j=1; $j <= $request->Quantity_Columns ; $j++) 
				{ 
					array_push($data, [
						'Name'               => $find->Symbols.'-'.$i.'-'.$j,
						'Symbols'            => $find->Symbols.'-'.$i.'-'.$j,
						'Warehouse_ID'       => $find->ID,
						'MAC'                => $find->MAC,
						'Quantity_Unit'      => $find->Quantity_Unit,
						'Unit_ID'            => $find->Unit_ID,
						'Quantity_Packing'   => $find->Quantity_Packing,
						'Packing_ID'         => $find->Packing_ID,
						'Accept'			 => $request->Accept,
						'Email'				 => $request->Email,
						'Email2'				 => $request->Email2,
						'Group_Materials_ID' => $find->Group_Materials_ID,
						'Floor'              => $request->Floor,
					]);
				}
			}
			// dd($data);
			$find->detail()->createMany($data);

			$status = __('Create').' '.__('Success');
		} 
		else
		{
			
			if (!Auth::user()->checkRole('update_master')) 
			{
				abort(401);	
			}
			// dd('run');
			// dd($find);
			$find->detail()->update([
				'User_Updated' => Auth::user()->id,
				'Floor'        => $request->Floor,
				'IsDelete'     => 1
			]);

			$data = array();

			for ($i=1; $i <= $request->Quantity_Rows ; $i++) 
			{ 
				for ($j=1; $j <= $request->Quantity_Columns ; $j++) 
				{
					$child = $find->update_detail()
					// ->where('Name', $find->Symbols.'-'.$i.'-'.$j)
					->where('Symbols', $find->Symbols.'-'.$i.'-'.$j)
					->first();
					if ($child) 
					{
						$child->update([
							'Name'               => $find->Symbols.'-'.$i.'-'.$j,
							'Symbols'            => $find->Symbols.'-'.$i.'-'.$j,
							'Warehouse_ID'       => $find->ID,
							'MAC'                => $request->MAC,
							'Quantity_Unit'      => $request->Quantity_Unit,
							'Unit_ID'            => $request->Unit_ID,
							'Quantity_Packing'   => $request->Quantity_Packing,
							'Packing_ID'         => $request->Packing_ID,
							'Accept'			 => $request->Accept,
							'Email'				 => $request->Email,
							'Email2'				 => $request->Email2,
							'Group_Materials_ID' => $request->Group_Materials_ID,
							'Floor'              => $request->Floor,
							'IsDelete'           => 0
						]);
					} else
					{
						$find->update_detail()->create([
							'Name'               => $find->Symbols.'-'.$i.'-'.$j,
							'Symbols'            => $find->Symbols.'-'.$i.'-'.$j,
							'Warehouse_ID'       => $find->ID,
							'MAC'                => $request->MAC,
							'Quantity_Unit'      => $request->Quantity_Unit,
							'Unit_ID'            => $request->Unit_ID,
							'Quantity_Packing'   => $request->Quantity_Packing,
							'Packing_ID'         => $request->Packing_ID,
							'Accept'			 => $request->Accept,
							'Email'				 => $request->Email,
							'Email2'				 => $request->Email2,
							'Group_Materials_ID' => $request->Group_Materials_ID,
							'Floor'              => $request->Floor,
							'IsDelete'           => 0
						]);
					}
					
				}
			}

			$find->Name               = $request->Name;
			$find->Symbols            = $request->Symbols;
			$find->Quantity_Rows      = $request->Quantity_Rows;
			$find->Quantity_Columns   = $request->Quantity_Columns;
			$find->MAC                = $request->MAC;
			$find->Quantity_Unit      = $request->Quantity_Unit;
			$find->Unit_ID            = $request->Unit_ID;
			$find->Quantity_Packing   = $request->Quantity_Packing;
			$find->Packing_ID         = $request->Packing_ID;
			$find->Group_Materials_ID = $request->Group_Materials_ID;
			$find->Accept             = $request->Accept;
			$find->Email              = $request->Email;
			$find->Email2              = $request->Email2;
			$find->Note               = $request->Note;
			$find->Floor              = $request->Floor;
			$find->User_Updated       = Auth::user()->id;
			$find->IsDelete           = 0;
			$find->save();

			$status = __('Update').' '.__('Success');
		}

		return (object)[
			'status' => $status,
			'data'   => $find
		];
	}

	public function destroy($request)
	{
		$find = MasterWarehouse::where('IsDelete', 0)
		->where('ID', $request->ID)
		->first();

		$find->User_Updated = Auth::user()->id;
		$find->IsDelete     = 1;
		$find->save();

		$find->detail()->update(['User_Updated' => Auth::user()->id, 'IsDelete' => 1]);

		return $find;
	}

	public function get_list_materials_in_warehouse($request)
	{
		$find = MasterWarehouse::where('IsDelete', 0)
		->where('ID', $request->Warehouse_ID)
		->first();
		$arr = [];
	
		if($find->detail)
		{
			foreach($find->detail as $value)
			{
				
				if($value->inventory)
				{
					foreach($value->inventory->GroupBy('Materials_ID') as $key => $value1)
					{
						
						$arr1 = [
							'Materials_ID'=>$key,
							'Materials'=>$value1[0]->materials ? $value1[0]->materials->Symbols : '',
							'Quantity' => number_format(Collect($value1)->sum('Inventory'), 2, '.', ''),
							'Count' => Count($value1)
						];
						array_push($arr,$arr1);
					}
				}
			}
		}
		return($arr);
	}
}

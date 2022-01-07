<?php
namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterGroupMaterials;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Auth;

class MasterGroupMaterialsLibraries
{
	public function get_all_list_group_materials()
	{
		return MasterGroupMaterials::where('IsDelete', 0)
		->with([
			'unit',
			'user_created',
			'user_updated'
		])
		->get();
	}

	public function filter($request)
	{
		$id 	 = $request->ID;
		$name 	 = $request->Name;
		$symbols = $request->Symbols;

		$data 	 = MasterGroupMaterials::when($id, function($query, $id)
		{
			return $query->where('ID', $id);
		})->when($name, function($query, $name)
		{
			return $query->where('Name', $name);
		})->when($symbols, function($query, $symbols)
		{
			return $query->where('Symbols', $symbols);
		})->where('IsDelete', 0)->get();

		return $data;
	}

	public function check_group_materials($request)
	{
		$id = $request->ID;
		$message = [
			'unique'   => $request->Symbols.' '.__('Already Exists').'!',
		];
		Validator::make($request->all(), 
		[
	        'Name' => 
	        [
	        	'required',
	        	'max:255',
		        Rule::unique('Master_Group_Materials')->where(function($q) use ($id) 
		        {
		        	$q->where('ID', '!=', $id)->where('IsDelete',0);
		        })
	    	]
	    ], $message)->validate();
	}

	public function add_or_update($request)
	{
		$id = $request->ID;
		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;
		if (isset($id) && $id != '') 
		{
			if (!Auth::user()->checkRole('update_master') && Auth::user()->level != 9999) 
			{
				abort(401);	
			}

			$data = MasterGroupMaterials::where('ID', $id)->update([
				'Name' 			=> $request->Name,
				'Symbols'		=> $request->Name,
				'Quantity'		=> $request->Quantity,
				'Unit_ID'		=> $request->Unit_ID,
				'Note'			=> $request->Note,
				'User_Updated'	=> $user_updated
			]);

			return (object)[
				'status' => __('Update').' '.__('Success'),
				'data'   => $data
			];
		} else
		{
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) 
			{
				abort(401);	
			}

			$data = MasterGroupMaterials::create([
				'Name' 			=> $request->Name,
				'Symbols'		=> $request->Name,
				'Quantity'		=> $request->Quantity,
				'Unit_ID'		=> $request->Unit_ID,
				'Note'			=> $request->Note,
				'User_Created'	=> $user_created,
				'User_Updated'	=> $user_updated,
				'IsDelete'		=> 0
			]);

			return (object)[
				'status' => __('Create').' '.__('Success'),
				'data'   => $data
			];
		}
	}

	public function add_or_update_file_excel($request)
	{
		$find = MasterGroupMaterials::where('IsDelete', 0)
		->where('Symbols', $request->Symbols)->first();

		if ($find) 
		{
			$find->Quantity = $request->Quantity;
			$find->User_Updated = Auth::user()->id;
			$find->IsDelete = 0;
			$find->save();
		} else
		{
			$find = MasterGroupMaterials::create([
				'Name'			=> $request->Name,
				'Symbols' 		=> $request->Symbols,
				'Quantity' 		=> $request->Quantity,
				'Unit_ID'  		=> $request->Unit_ID,
				'User_Updated' 	=> Auth::user()->id,
				'User_Created' 	=> Auth::user()->id
			]);
		}

		return (object)[
			'status' => '',
			'data'	 => $find->refresh()
		];
	}

	public function destroy($request)
	{
		$find = MasterGroupMaterials::where('ID', $request->ID)->first();
		$status = __('Delete').' '.__('Fail');
		if ($find) 
		{
			$find->update([
				'IsDelete' 		=> 1,
				'User_Updated'	=> Auth::user()->id
			]);

			$find->group()->update([
				'IsDelete' 		=> 1,
				'User_Updated'	=> Auth::user()->id
			]);

			$status = __('Delete').' '.__('Success');

		}

		return $status;
	}
	
}
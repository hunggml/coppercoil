<?php
namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\GroupMaterials;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Auth;

class GroupMaterialsLibraries
{
	public function get_all_list_group()
	{
		return GroupMaterials::where('IsDelete', 0)
		->with([
			'user_created',
			'user_updated'
		])
		->get();
	}

	public function filter($request)
	{
		$id 	 = $request->ID;
		$name 	 = $request->Group_ID;
		$symbols = $request->Materials_ID;

		$data 	 = GroupMaterials::when($id, function($query, $id)
		{
			return $query->where('ID', $id);
		})->when($name, function($query, $name)
		{
			return $query->where('Group_ID', $name);
		})->when($symbols, function($query, $symbols)
		{
			return $query->where('Materials_ID', $symbols);
		})->where('IsDelete', 0)->get();

		return $data;
	}

	public function add_or_update($request)
	{
		$find = GroupMaterials::where('Group_ID', $request->Group_ID)
		->where('Materials_ID', $request->Materials_ID)
		->first();

		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;

		if ($find) 
		{
			if (!Auth::user()->checkRole('update_master') && Auth::user()->level != 9999) 
			{
				abort(401);	
			}

			$find->update([
				'Group_ID'     => $request->Group_ID,
				'Materials_ID' => $request->Materials_ID,
				'User_Updated' => $user_updated,
				'IsDelete' 	   => 0
			]);

			return __('Update').' '.__('Success');
		} else
		{
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) 
			{
				abort(401);	
			}

			GroupMaterials::create([
				'Group_ID'     => $request->Group_ID,
				'Materials_ID' => $request->Materials_ID,
				'User_Created' => $user_created,
				'User_Updated' => $user_updated,
				'IsDelete'     => 0
			]);

			return __('Create').' '.__('Success');
		}
	}

	public function destroy($request)
	{
		GroupMaterials::where('ID', $request->ID)->update([
			'IsDelete' 		=> 1,
			'User_Updated'	=> Auth::user()->id
		]);

		return __('Delete').' '.__('Success');
	}

	public function destroy_all($request)
	{
		GroupMaterials::where('Group_ID', $request->Group_ID)->update([
			'IsDelete' 		=> 1,
			'User_Updated'	=> Auth::user()->id
		]);

		return __('Delete').' '.__('Success');
	}
	
}
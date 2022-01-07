<?php
namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterSupplier;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Auth;

class MasterSupplierLibraries
{
	public function get_all_list_supplier()
	{
		return MasterSupplier::where('IsDelete', 0)
		->with([
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

		$data 	 = MasterSupplier::when($id, function($query, $id)
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

	public function check_Supplier($request)
	{
		$id = $request->ID;
		$message = [
			'unique'   => $request->Symbols.' '.__('Already Exists').'!',
		];
		Validator::make($request->all(), 
		[
	        'Symbols' => ['required','max:255',
	        Rule::unique('Master_Supplier')->where(function($q) use ($id) 
	        {
	        	$q->where('ID', '!=', $id)->where('IsDelete',0);
	        })]
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

			$supplier = MasterSupplier::where('ID', $id)->update([
				'Name'         => $request->Name,
				'Symbols'      => $request->Symbols,
				'Address'      => $request->Address,
				'Contact'      => $request->Contact,
				'Phone'        => $request->Phone,
				'Tax_Code'     => $request->Tax_Code,
				'Note'         => $request->Note,
				'User_Updated' => $user_updated
			]);

			return (object)[
				'status' => __('Update').' '.__('Success'),
				'data'	 => $supplier
			];
		} else
		{
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) 
			{
				abort(401);	
			}

			$supplier = MasterSupplier::create([
				'Name'         => $request->Name,
				'Symbols'      => $request->Symbols,
				'Address'      => $request->Address,
				'Contact'      => $request->Contact,
				'Phone'        => $request->Phone,
				'Tax_Code'     => $request->Tax_Code,
				'Note'         => $request->Note,
				'User_Created' => $user_created,
				'User_Updated' => $user_updated,
				'IsDelete'     => 0
			]);

			return (object)[
				'status' => __('Create').' '.__('Success'),
				'data'	 => $supplier
			];
		}
	}

	public function destroy($request)
	{
		MasterSupplier::where('ID', $request->ID)->update([
			'IsDelete' 		=> 1,
			'User_Updated'	=> Auth::user()->id
		]);

		return __('Delete').' '.__('Success');
	}
	
}
<?php

namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterMachine;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Auth;

class MasterMachineLibraries
{
	public function get_all_list_machine()
	{
		return MasterMachine::where('IsDelete', 0)
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

		$data 	 = MasterMachine::when($id, function ($query, $id) {
			return $query->where('ID', $id);
		})->when($name, function ($query, $name) {
			return $query->where('Name', $name);
		})->when($symbols, function ($query, $symbols) {
			return $query->where('Symbols', $symbols);
		})->where('IsDelete', 0)->get();

		return $data;
	}

	public function check_machine($request)
	{
		$id = $request->ID;
		$message = [
			'unique.Symbols'        => $request->Symbols . ' ' . __('Already Exists') . '!'
		];
		Validator::make(
			$request->all(),
			[
				'Symbols' => [
					'required',
					'max:255',
					Rule::unique('Master_Machine')->where(function ($q) use ($id) {
						$q->where('ID', '!=', $id)->where('IsDelete', 0);
					})
				]
			],
			$message
		)->validate();
	}

	public function add_or_update($request)
	{
		$id           = $request->ID;
		$user_created = Auth::id();
		$user_updated = Auth::id();
		if (isset($id) && $id != '') {
			if (!Auth::user()->checkRole('update_master') && Auth::user()->level != 9999) {
				abort(401);
			}

			$machine = MasterMachine::where('ID', $id)->update([
				'Name'         => $request->Name,
				'Symbols'      => $request->Symbols,
				// 'IP'           => $request->IP,
				// 'MAC'          => $request->MAC,
				// 'Warehouse_ID' => $request->Warehouse_ID,
				'Note'         => $request->Note,
				'User_Updated' => $user_updated
			]);

			return (object)[
				'status' => __('Update') . ' ' . __('Success'),
				'data'	 => $machine
			];
		} else {
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) {
				abort(401);
			}

			$machine = MasterMachine::create([
				'Name'         => $request->Name,
				'Symbols'      => $request->Symbols,
				// 'IP'           => $request->IP,
				// 'MAC'          => $request->MAC,
				// 'Warehouse_ID' => $request->Warehouse_ID,
				'Note'         => $request->Note,
				'User_Created' => $user_created,
				'User_Updated' => $user_updated,
				'IsDelete'     => 0
			]);

			return (object)[
				'status' => __('Create') . ' ' . __('Success'),
				'data'	 => $machine
			];
		}
	}

	public function destroy($request)
	{
		MasterMachine::where('ID', $request->ID)->update([
			'IsDelete' 		=> 1,
			'User_Updated'	=> Auth::user()->id
		]);

		return __('Delete') . ' ' . __('Success');
	}
}

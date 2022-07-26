<?php
namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterQC;
use App\Models\MasterData\MasterHandleError;
// use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
// use Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MasterErrorLibraries
{
	public function get_all_list_error()
	{
		return MasterQC::where('IsDelete', 0)
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

		$data 	 = MasterQC::when($id, function($query, $id)
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

	public function check_error($request)
	{
		$id = $request->ID;
		$message = [
			'unique'   => $request->Name.' '.__('Already Exists').'!',
		];

		Validator::make($request->all(), 
		[
	        'Name' => ['required','max:255',
	        Rule::unique('Master_QC')->where(function($q) use ($id) 
	        {
	        	$q->where('ID', '!=', $id)->where('IsDelete',0);
	        })]
	    ], $message)->validate();
	}

	public function add_or_update($request)
	{
        // dd($request);
		$id = $request->ID;
		$user_created = Auth::user()->id;
		$user_updated = Auth::user()->id;
		if (isset($id) && $id != '') 
		{
			if (!Auth::user()->checkRole('update_master') && Auth::user()->level != 9999) 
			{
				abort(401);	
			}

			$error = MasterQC::where('ID', $id)->update([
				'Name' 			=> $request->Name,
				'Note'			=> $request->Note,
				'User_Updated'	=> $user_updated
			]);
            MasterHandleError::where('QC_ID',$id)->update([
                'IsDelete'=>1
            ]);
            if(isset($request->ListHandle))
            {
                foreach($request->ListHandle as $value)
                {
                    MasterHandleError::create([
                        'QC_ID'=>$id,
                        'Handle'=>$value,
                        'User_Created'	=> $user_created,
                        'User_Updated'	=> $user_updated,
                        'IsDelete'		=> 0
                    ]);
                }
            }
            
			return (object)[
				'status' => __('Update').' '.__('Success'),
				'data'	 => $error
			];
		} else
		{
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) 
			{
				abort(401);	
			}

			$error = MasterQC::create([
				'Name' 			=> $request->Name,
				'Note'			=> $request->Note,
				'User_Created'	=> $user_created,
				'User_Updated'	=> $user_updated,
				'IsDelete'		=> 0
			]);
            if(isset($request->ListHandle))
            {
                foreach($request->ListHandle as $value)
                {
                    MasterHandleError::create([
                        'QC_ID'=>$error->ID,
                        'Handle'=>$value,
                        'User_Created'	=> $user_created,
                        'User_Updated'	=> $user_updated,
                        'IsDelete'		=> 0
                    ]);
                }
            }
			return (object)[
				'status' => __('Create').' '.__('Success'),
				'data'	 => $error
			];
		}
	}

	public function destroy($request)
	{
		MasterQC::where('ID', $request->ID)->update([
			'IsDelete' 		=> 1,
			'User_Updated'	=> Auth::user()->id
		]);

		return __('Delete').' '.__('Success');
	}
	
}
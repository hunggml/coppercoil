<?php
namespace App\Libraries\MasterData;

use Illuminate\Validation\Rule;
use App\Models\MasterData\MasterProduct;
use App\Models\MasterData\MasterMaterials;
use App\Models\MasterData\MasterUnit;
use App\Models\MasterData\MasterBOM;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use Auth;

class MasterProductLibraries
{
	public function get_all_list_product()
	{
		return MasterProduct::where('IsDelete', 0)
		->with([
			'user_created',
			'user_updated',
			'unit',
			'materials'
		])
		->get();
	}

	public function filter($request)
	{
		$id 	 = $request->ID;
		$name 	 = $request->Name;
		$symbols = $request->Symbols;

		$data 	 = MasterProduct::when($id, function($query, $id)
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

	public function check_product($request)
	{
		$id = $request->ID;
		$message = [
			'unique'   => $request->Symbols.' '.__('Already Exists').'!',
		];
		Validator::make($request->all(), 
		[
	        'Symbols' => ['required','max:255',
	        Rule::unique('Master_Product')->where(function($q) use ($id) 
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
		dd($request);
		if (isset($id) && $id != '') 
		{
			if (!Auth::user()->checkRole('update_master') && Auth::user()->level != 9999) 
			{
				abort(401);	
			}

			$product = MasterProduct::where('ID', $id)->update([
				'Name'         => $request->Name,
				'Symbols'      => $request->Symbols,
				'Unit_ID'      => $request->Unit_ID,
				'Materials_ID' => $request->Materials_ID,
				'Note'		   => $request->Note,
				'Quantity'	   => $request->Quantity,
				'User_Updated' => $user_updated
			]);

			return (object)[
				'status' => __('Update').' '.__('Success'),
				'data'	 => $product
			];
		} else
		{
			if (!Auth::user()->checkRole('create_master') && Auth::user()->level != 9999) 
			{
				abort(401);	
			}

			$product = MasterProduct::create([
				'Name' 			=> $request->Name,
				'Symbols'		=> $request->Symbols,
				'Unit_ID'		=> $request->Unit_ID,
				'Materials_ID'  => $request->Materials_ID,
				'Note'			=> $request->Note,
				'Quantity'	   	=> $request->Quantity,
				'User_Created'	=> $user_created,
				'User_Updated'	=> $user_updated,
				'IsDelete'		=> 0
			]);

			return (object)[
				'status' => __('Create').' '.__('Success'),
				'data'	 => $product
			];
		}
	}

	public function destroy($request)
	{
		$find = MasterProduct::where('ID', $request->ID)->first();

		$find->update([
			'IsDelete' 		=> 1,
			'User_Updated'	=> Auth::user()->id
		]);

		return __('Delete').' '.__('Success');
	}

	private function read_file($request)
    {
        try
        {
            $file     = request()->file('fileImport');
            $name     = $file->getClientOriginalName();
            $arr      = explode('.', $name);
            $fileName = strtolower(end($arr));
            // dd($file, $name, $arr, $fileName);
            if ($fileName != 'xlsx' && $fileName != 'xls') 
            {
                return redirect()->back();
            } 
            else if($fileName == 'xls')
            {
                $reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else if($fileName == 'xlsx')
            { 
                $reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            try
            {
                $spreadsheet = $reader->load($file);
                $data        = $spreadsheet->getActiveSheet()->toArray();

                return $data;
            }  
            catch(\Exception $e)
            {
                return ['danger' => __('Select The Standard ".xlsx" Or ".xls" File')];
            }
        } 
        catch (\Exception $e)
        {
            return ['danger' => __('Error Something')];
        }
    }
    
	public function import_file($request)
    {
        $data = $this->read_file($request);
        $im = [];
        $err = [];
		
        foreach($data as $key => $value)
        {
            if($key>0)
            {
				if($value[2] != ''  && $value[1])
				{
					$sym = explode('|',$value[2]);
					// dd($sym);
					if(count($sym) > 1)
					{

						$mater = MasterMaterials::where('IsDelete',0)
						->where('Symbols',$sym[0])
						->where('Spec',$sym[1])
						->first();
						$unit = MasterUnit::where('IsDelete',0)
						->where('Symbols',$value[4])
						->first();
						// dd($mater,$unit,$value[4]);
						if($mater && $unit)
						{
							$pro = MasterProduct::where('IsDelete',0)->where('Symbols',$value[1])->first();
							if($pro)
							{
								MasterProduct::where('ID',$pro->ID)->update([
									'Materials_ID'=>$mater->ID,
									'Quantity'=>$value[3],
									'Unit_ID'=>$unit->ID
								]);
							}
							else
							{
								MasterProduct::create([
									'Name'=>$value[0],
									'Symbols'=>$value[1],
									'Materials_ID'=>$mater->ID,
									'Quantity'=>$value[3],
									'Unit_ID'=>$unit->ID
								]);	
							}
						}
						else
						{
							$err1 = 'Dòng '.($key+1).' Có mã NVL , Đơn vị tính không tồn tại';
							array_push($err,$err1);
						}
					}
				}
				
            	
			}
        }
       
       
       return $err;
    }  

}
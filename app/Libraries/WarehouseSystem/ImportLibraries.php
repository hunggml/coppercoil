<?php
namespace App\Libraries\WarehouseSystem;

use Illuminate\Validation\Rule;
use App\Models\WarehouseSystem\CommandImport;
use App\Models\WarehouseSystem\ImportDetail;
use Validator;
use App\Models\MasterData\MasterMaterials;
use App\Models\MasterData\MasterWarehouse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ImportLibraries
{

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

    public function get_list_all_command()
    {
        return CommandImport::where('IsDelete',0)
        ->orderBy('Time_Created','desc')
        ->get();
    }
    public function detail($request)
    {
        $materials = $request->Materials_ID;
        $Pallet = $request->Pallet_ID;
        $data =  ImportDetail::where('IsDelete',0)
        ->when($materials, function($query, $materials)
		{
			return $query->where('Materials_ID', $materials);
		})
        ->when($Pallet, function($query, $Pallet)
		{
			return $query->where('Pallet_ID', $Pallet);
		})
        ->where('Command_ID',$request->ID)
        ->get();
        // dd($data);

        // dd($request->Status);
        $arr = [];
        foreach( $data as $value )
        {
            if($request->Status === '0' ) 
            {   
                
                if($value->materials && $value->Status == $request->Status)
                {
                    if(($request->Wire_Type ? ($request->Wire_Type == $value->materials->Wire_Type ? 1: 0 ) : 1) )
                    {
                        array_push($arr,$value);
                    }
                }
            }
            else 
            {
                if($request->Status)
                {
                    if($value->materials && $value->Status == $request->Status)
                    {
                        if(($request->Wire_Type ? ($request->Wire_Type == $value->materials->Wire_Type ? 1: 0 ) : 1) )
                        {
                            array_push($arr,$value);
                        }
                    }
                }

                else 
                {
                    if($value->materials)
                    {
                        if(($request->Wire_Type ? ($request->Wire_Type == $value->materials->Wire_Type ? 1: 0 ) : 1) )
                        {
                            array_push($arr,$value);
                        }
                    }
                }

               
            }
            
        }
        return collect($arr);
    }
    public function detail_all_list($request)
    {
        $ware = $request->warehouse;
        $location = $request->location;
        $materials = $request->Materials_ID;
        $Pallet = $request->Pallet_ID;
        $warehouse = MasterWarehouse::where('IsDelete',0)
        ->when($ware, function($query, $ware)
		{
			return $query->where('ID', $ware);
		})
        ->get();
        $arr = [];
        foreach($warehouse as $value )
        { 
            $check = false;
            $count = 0;
            $arr1 = [
                'Name' => $value->Name,
                'Symbols'=>$value->Symbols,
            ];
            $arr3 = [];
           
            foreach($value->detail->when($location, function($query, $location)
            {
                return $query->where('ID', $location);
            }) as $value1)
            {
                $arr2 = [
                    'Name' => $value1->Name,
                    'Symbols'=>$value1->Symbols,
                ];
                $count1 = 0;
                if(count($value1->inventory->when($materials, function($query, $materials)
                {
                    return $query->where('Materials_ID', $materials);
                })->when($Pallet, function($query, $Pallet)
                {
                    return $query->where('Pallet_ID', $Pallet);
                })) > 0)
                {
                    $arr5 = [];
                   
                    foreach( $value1->inventory->when($materials, function($query, $materials)
                    {
                        return $query->where('Materials_ID', $materials);
                    })->when($Pallet, function($query, $Pallet)
                    {
                        return $query->where('Pallet_ID', $Pallet);
                    })
                    ->GroupBy('Pallet_ID') as $value2
                    )
                    {
                        foreach($value2->GroupBy('Materials_ID') as $value3)
                        {
                            if(count($value3) == 1 )
                            {
                                $count2 =-1;
                                $count =1;
                                $count1 =-1;
                            }
                            else
                            {
                                $count2 = count($value3)+1;
                                $count +=count($value3)+4;
                                $count1 +=count($value3)+2;
                            }

                            
                            $arr4 = [
                                'Pallet_ID' => $value3[0]->Pallet_ID,
                                'Materials'=>$value3[0]->materials ? $value3[0]->materials->Symbols : '',
                                'Inven' => $value3,
                                'Time_Import' => $value3[0]->Time_Import,
                                'Time_Updated' => $value3[0]->Time_Updated,
                                'Count'=>$count2
                            ];
                           
                            array_push($arr5,$arr4);
                        }
                       
                    }
                    $arr2['Inven'] = $arr5;
                    // $count += 1;
                    // $count1 += 1 ;
                    $check = true; 
                    $arr2['Count'] = $count1;
                    array_push($arr3,$arr2);
                }
                
            }
            if($check)
            {
                $arr1['Inven'] = $arr3;
                $arr1['Count'] = $count;
                array_push($arr,$arr1);
            }
        }
        // dd($arr);
        return collect($arr);
    }
    public function list_pallet()
    {
        return ImportDetail::where('IsDelete',0)->where('Inventory','>',0)
        ->get();
    }
    public function get_all()
    {
        return ImportDetail::where('IsDelete',0)
        ->get();
    }
    public function detail_box($request)
    {
        return ImportDetail::where('IsDelete',0)
        ->where('Box_ID',$request->Box_ID)
        ->with(['materials'])
        ->first();
    }
    public function detail_all($request)
    {
        return ImportDetail::where('IsDelete',0)->where('Command_ID',$request->ID)
        ->get();
    }
    public function get_list($request)
    {
       
        $data =  ImportDetail::where('IsDelete',0)
        ->where('Pallet_ID',$request->Pallet_ID)
        // ->where('Inventory','>',0)
        ->where('Type',0)
        ->where('Status','!=',2)
        ->with('materials','location')
        ->get();
        if(count($data) > 0)
        {
            $val = [];
            $val['Pallet_ID'] = $data[0]->Pallet_ID;
            $val['Status']    = $data[0]->Status;
            $val['Materials'] = $data[0]->materials ? $data[0]->materials->Symbols : '';
            $val['Materials_ID'] = $data[0]->materials ? $data[0]->materials->ID : '';
            $val['Wire_Type'] = $data[0]->materials ? $data[0]->materials->Wire_Type : '';
            $val['Spec'] = $data[0]->materials ? $data[0]->materials->Spec   : '';
            $val['Quantity'] = number_format(collect($data)->sum('Quantity'), 2, '.', '');
            $val['Count'] = count($data);
            $val['List'] = $data;
            return $val;
        }
        
        
    }


	public function get_list_command_import($request)
    {
        $name = $request->name;
        $from = $request->from;
        $to   = $request->to;
        return CommandImport::where('IsDelete',0)
        ->when($name, function($query, $name)
		{
			return $query->where('Name', $name);
		})->when($from, function($query, $from )
		{
			return $query->where('Time_Created', '>=' , Carbon::create($from)->startOfDay()->toDateTimeString());
		})
        ->when($to, function($query, $to)
		{
			return $query->where('Time_Created', '<=', Carbon::create($to)->endOfDay()->toDateTimeString());
		})
        ->orderBy('Time_Created','desc')
        ->paginate(10);
    }


    public function import_file_excel($request)
    {
        $data = $this->read_file($request);
        $im = [];
        $err = [];
        foreach($data as $key => $value)
        {
            if($key>3)
            {
            //    dd($value);
               $mater = MasterMaterials::where('Wire_Type',$value[6])->where('Spec',trim($value[5]))->first();
               if($mater)
               {
                   
                   $arr = [
                       'Materials_ID'     =>$mater->ID,
                       'Box_ID'           =>$value[0],
                       'Case_No'          =>$value[1],
                       'Lot_No'           =>$value[2],
                       'Pallet_ID'        =>$value[3],
                       'Quantity'         =>$value[4],
                       'Packing_Date'     =>$value[8],
                       'Status'           =>0,
                       'Type'             =>0,
                       'User_Created'     => Auth::user()->id,
                       'User_Created'     => Auth::user()->id,
                       'IsDelete'         => 0
                   ];
                   $check = ImportDetail::where('Materials_ID',$mater->ID)
                   ->where('Box_ID',$value[0])
                   ->where('Case_No',$value[1])
                   ->where('Lot_No',$value[2])
                   ->where('Pallet_ID',$value[3])
                   ->where('IsDelete',0)
                   ->orderBy('ID','desc')
                   ->first();

                   $check2 = ImportDetail::where('IsDelete',0)
                   ->where('Box_ID',$value[0])
                   ->where('Inventory','>',0)
                   ->orderBy('ID','desc')
                   ->first();

                   if($check2)
                   {
                        $err1 = 'Box_ID '.($value[0]).' Đã Tồn Tại Trong Hệ Thống';
                        array_push($err,$err1);
                        return array_unique($err);
                   }
                   
                   if($check)
                   {
                        if($check->Status == 2)
                        {
                            array_push($im , $arr);
                        }
                        else
                        {
                            if($check->Status == 0)
                            {
                                ImportDetail::where('ID',$check->ID)->update(['Status'=>2]);
                                array_push($im , $arr);
                            }
                            else
                            {
                                $err1 = 'Pallet '.$value[3].' Đã Được Nhập Kho';
                                array_push($err,$err1);
                            }
                        }
                   }
                   else
                   {
                        array_push($im , $arr);
                   }
                   
               }
               else
               {
                   $err1 = 'WIRE_TYPE '.($value[6]).','.$value[5].' Không Tồn Tại';
                   array_push($err,$err1);
               }
            }
        }
       
        $date =  date_format(Carbon::now(),"Ymd");
        $stt = CommandImport::where('Time_Created','>=',Carbon::now()->startOfDay()->toDateTimeString())
        ->where('Time_Created','<=',Carbon::now()->endOfDay()->toDateTimeString())
        ->count();
        // dd(array_unique($err));
        if(count($im) > 0)
        {
            $cm = CommandImport::Create([
                'Name'=>$date.''.($stt+1),
                'Note'=>$request->Note,
                'User_Created'     => Auth::user()->id,
                'User_Updated'     => Auth::user()->id,
                'IsDelete'         => 0
            ]);
        }
        foreach($im as $value)
        {
            $value['Command_ID'] = $cm->ID;
            ImportDetail::create($value);
        }
       return array_unique($err);
    }   

    public function get_list_retype($request)
    {
        $materials = $request->Materials_ID;
        $Status = $request->Status;
        $Box_ID = $request->Box_ID;
        $Pallet = $request->Pallet_ID;
        $from = $request->from;
        $to   = $request->to;
        return  ImportDetail::where('IsDelete',0)
        ->when($materials, function($query, $materials)
		{
			return $query->where('Materials_ID', $materials);
		})
        ->when($Box_ID, function($query, $Box_ID)
        {
            return $query->where('Box_ID', $Box_ID);
        })
        ->when($Status, function($query, $Status)
		{
			return $query->where('Status', $Status);
		})
        ->when($Pallet, function($query, $Pallet)
		{
			return $query->where('Pallet_ID', $Pallet);
		})->when($from, function($query, $from )
		{
			return $query->where('Time_Created', '>=' ,Carbon::create($from)->startOfDay()->toDateTimeString());
		})
        ->when($to, function($query, $to)
		{
			return $query->where('Time_Created', '<=' ,Carbon::create($to)->endOfDay()->toDateTimeString());
		})
        
        ->where('Type',1)
        ->orderBy('ID','DESC')
        ->paginate(10);
    }




    public function cancel($request)
    {
        return ImportDetail::where('IsDelete',0)
        ->where('Command_ID',$request->Command_ID)
        ->where('Materials_ID',$request->Materials_ID)
        ->where('Pallet_ID',$request->Pallet_ID)
        ->with('materials')
        ->update([
            'User_Updated'     => Auth::user()->id,
            'Status'         => 2
        ]);
    }
    public function add_stock($request)
    {
        $data =  ImportDetail::where('IsDelete',0)
        ->where('Pallet_ID',$request->Pallet_ID)
        ->where('Status',0)
        ->with('materials')
        ->get();
        foreach($data as $value)
        {
            $data =  ImportDetail::where('IsDelete',0)
            ->where('ID',$value->ID)
            ->update([
                'User_Updated'     => Auth::user()->id,
                'Inventory'        =>$value->Quantity,
                'Time_Import'      =>Carbon::now(), 
                'Warehouse_Detail_ID'         => $request->Warehouse_Detail_ID,
                'Status'          => 1
            ]);
        }
        return __('Add Stock Success');
    }
    public function destroy($request)
    {
        return ImportDetail::where('IsDelete',0)
        ->where('ID',$request->ID)
        ->update([
            'User_Updated'     => Auth::user()->id,
            'IsDelete'         => 1
        ]);
    }

    public function check_infor($request)
    {
        // dd($run);
        return ImportDetail::where('IsDelete',0)
        ->where('Box_ID',$request->Box_ID)
        ->where('Status','!=',2)
        ->orderBy('ID','desc')
        ->with('materials','location')
        ->first();
    }

    public function retype_add($request)
    {
        
        $val =  ImportDetail::where('IsDelete',0)
        ->where('Box_ID',$request->Box)
        ->where('Status','!=',2)
        ->orderBy('ID','desc')
        ->with('materials','location')
        ->first();
       
        if($request->Type == 1)
        {
            $arr = [
                'Materials_ID'     => $val->Materials_ID,
                'Box_ID'           => $request->Box,
                'Case_No'          => $val->Case_No,
                'Lot_No'           => $val->Lot_No,
                'Time_Import'      => $val->Time_Import, 
                'Pallet_ID'        => '',
                'Quantity'         => $request->Quantity,
                'Inventory'        => $request->Quantity,
                'Warehouse_Detail_ID' => $request->Warehouse_Detail_ID,
                'Status'           => 1,
                'Type'             => $request->Type,
                'User_Created'     => Auth::user()->id,
                'User_Updated'     => Auth::user()->id,
                'IsDelete'         => 0
            ];
            ImportDetail::create($arr);
        }
        else
        {
            // dd($val);
            $data =  ImportDetail::where('IsDelete',0)
            ->where('ID',$val->ID)
            ->update([
                'User_Updated'     => Auth::user()->id,
                'Inventory'        => $request->Quantity,
                'Warehouse_Detail_ID'=> $request->Warehouse_Detail_ID,
                'Status'           => 0
            ]);
        }
        
    }

    public function get_list_with_materials($request)
    {
        return ImportDetail::where('IsDelete',0)
        ->where('Materials_ID',$request->Materials_ID)
        ->where('Inventory','>',0)
        ->whereIn('Warehouse_Detail_ID',$request->warehouse)
        ->orderBy('Time_Import')
        ->get();
    }

    public function add_pallet($request)
    {
        if($request->Pallet_ID)
        {
            if($request->Materials_ID)
            {
                if($request->Box)
                {
                    if(count($request->Box))
                    {
                        foreach($request->Box as $key => $value )
                        {
                            $arr = [
                                'Command_ID'  => $request->Command_ID,
                                'Materials_ID'     => $request->Materials_ID,
                                'Box_ID'           => $value,
                                'Case_No'          => '',
                                'Lot_No'           => '',
                                'Time_Import'      => '', 
                                'Pallet_ID'        => $request->Pallet_ID,
                                'Quantity'         => $request->Quantity[$key],
                                'Inventory'        => '',
                                'Warehouse_Detail_ID' => '',
                                'Status'           => 0,
                                'Type'             => 0,
                                'User_Created'     => Auth::user()->id,
                                'User_Updated'     => Auth::user()->id,
                                'IsDelete'         => 0
                            ];
                            ImportDetail::create($arr);
                           
                        }
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }


    
}      

<?php
namespace App\Libraries\WarehouseSystem;

use Illuminate\Validation\Rule;
use App\Models\WarehouseSystem\CommandInventory;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\WarehouseSystem\InventoryMaterials;
use App\Models\WarehouseSystem\ExportDetail;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Models\MasterData\MasterMaterials;
use App\Models\MasterData\MasterWarehouse;
use App\Models\MasterData\MasterWarehouseDetail;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class InventoryLibraries
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
    public function list_inventory($request)
    {
        $name = $request->name;
        $from = $request->from;
        $to = $request->to;
        return CommandInventory::where('IsDelete',0)
        ->when($name, function($query, $name)
		{
			return $query->where('Name', $name);
		})
        ->when($from, function($query, $from )
		{
			return $query->where('Time_Created', '>=' , Carbon::create($from)->startOfDay()->toDateTimeString());
		})
        ->when($to, function($query, $to)
		{
			return $query->where('Time_Created', '<=' ,Carbon::create($to)->endOfDay()->toDateTimeString());
		})
        ->orderBy('Time_Created','desc')
        ->paginate(10);
    }
    public function data_all_inventory($request)
    {
        
        return CommandInventory::where('IsDelete',0)
        ->orderBy('Time_Created','desc')
        ->get();
    }

    public function command($request)
    {
        return CommandInventory::where('IsDelete',0)
        ->where('ID',$request->ID)
        ->orderBy('Time_Created','desc')
        ->first();
    }
    public function list_inventory_with_command($request)
    {
        
        return InventoryMaterials::where('IsDelete',0)
        ->where('Command_Inventories_ID',$request->ID)
        ->orderBy('Warehouse_System_ID','asc')
        ->get();
    }
    public function detail_inven($request)
    {
        $id = $request->ID;
        $pallet = $request->Pallet_ID;
        $command_id = $request->Command_ID;
        return InventoryMaterials::where('IsDelete',0)
        ->when($id, function($query, $id)
		{
			return $query->where('ID', $id);
		})
        ->when($command_id, function($query, $command_id)
        {
            return $query->where('Command_Inventories_ID', $command_id);
        })
        ->when($pallet, function($query, $pallet)
		{
			return $query->where('Pallet_System_ID', $pallet);
		})
        ->orderBy('Time_Created','desc')
        ->get();
    }

    public function update_inventory($request)
    {
        $pallet = $request->Pallet_ID;
        $command_id = $request->Command_ID;
        $location = $request->Location;
        $label = $request->Box;

        $data = InventoryMaterials::where('IsDelete',0)
        ->where('Command_Inventories_ID',$command_id)
        ->where('Warehouse_System_ID',$location)
        ->where('Pallet_System_ID',$pallet)
        ->where('Box_System_ID',$label)
        ->first();
        // dd($data);
        if($data)
        {
            if($data->Quantity_System != $request->Quantity)
            {
            // dd('run1');

                $dataUpdate = [
                    'Box_ID'           => $label,
                    'Quantity'         => $request->Quantity,
                    'Status'           => 1,
                    'User_Created'     => Auth::user()->id,
                    'User_Updated'     => Auth::user()->id,
                    'IsDelete'         => 0
                ];
            }
            else
            {
            // dd('run2');

                $dataUpdate = [            
                    'Box_ID'           => $label,
                    'Quantity'         => $request->Quantity,
                    'Status'           => 3,
                    'User_Created'     => Auth::user()->id,
                    'User_Updated'     => Auth::user()->id,
                    'IsDelete'         => 0
                ];
            }
            // dd($dataUpdate);
            InventoryMaterials::where('ID',$data->ID)->update($dataUpdate);
            return true;
        }
        else
        {
            $value1  =  ImportDetail::where('IsDelete',0)
                ->where('Box_ID',$label)
                ->orderBy('Time_Created','desc')
                ->first();
            if($value1)
            {
                $arr1 = [
                'Command_Inventories_ID'=> $command_id,
                'Warehouse_System_ID'   => $location,
                'Pallet_System_ID'      => $pallet,
                'Box_ID'                => $label,
                'Quantity'              => $request->Quantity,
                'Status'                => 2,
                'Type'                  => 0,
                'User_Created'          => Auth::user()->id,
                'User_Updated'          => Auth::user()->id,
                'IsDelete'              => 0
                ];
                InventoryMaterials::create($arr1);
                return true;
            }
            else
            {
                return false;
            }
            
        }
       return false;
    } 

    public function success($request)
    {
        $data =  InventoryMaterials::where('IsDelete',0)
        ->where('Command_Inventories_ID',$request->Command_ID)
        ->get();
        // dd($data);
        foreach($data as $value)
        {
            if($value->Status == 1)
            {
                $value1  =  ImportDetail::where('IsDelete',0)
                ->where('Box_ID',$value->Box_System_ID)
                ->orderBy('Time_Created','desc')
                ->first();
                if($value1)
                {
                    $arr1 = [
                        'Export_ID' => '',
                        'Pallet_ID' =>  $value->Pallet_System_ID,
                        'Box_ID'    =>  $value->Box_System_ID,
                        'Materials_ID'=>    $value->Materials_System_ID,
                        'Warehouse_Detail_ID'=> $value->Warehouse_System_ID,
                        'Quantity'  =>  $value->Quantity_System,
                        'Status'    =>  1,
                        'Type'      =>  1,
                        'Time_Export'=>Carbon::now(),
                        'User_Created'     => Auth::user()->id,
                        'User_Updated'     => Auth::user()->id,
                        'IsDelete'         => 0
                    ];
                    ExportDetail::Create($arr1);

                    $arr = [
                        'Materials_ID'        => $value1->Materials_ID,
                        'Box_ID'              => $value1->Box_ID,
                        'Case_No'             => $value1->Case_No,
                        'Lot_No'              => $value1->Lot_No,
                        'Time_Import'         => $value1->Time_Import, 
                        'Pallet_ID'           => $value->Pallet_System_ID,
                        'Quantity'            => $value->Quantity,
                        'Inventory'           => $value->Quantity,
                        'Warehouse_Detail_ID' => $value->Warehouse_System_ID,
                        'Status'              => 1,
                        'Type'                => 2,
                        'User_Created'        => Auth::user()->id,
                        'User_Updated'        => Auth::user()->id,
                        'IsDelete'            => 0
                    ];
                    ImportDetail::create($arr);     
                    ImportDetail::where('ID',$value1->ID)
                    ->update([ 
                    'User_Updated'     => Auth::user()->id,
                    'Inventory'        => 0,
                    ]); 
                }
            }
            elseif($value->Status == 2)
            {
                $value1  =  ImportDetail::where('IsDelete',0)
                ->where('Box_ID',$value->Box_ID)
                ->orderBy('Time_Created','desc')
                ->first();
                if($value1)
                {
                    $arr = [
                        'Materials_ID'        => $value1->Materials_ID,
                        'Box_ID'              => $value1->Box_ID,
                        'Case_No'             => $value1->Case_No,
                        'Lot_No'              => $value1->Lot_No,
                        'Time_Import'         => $value1->Time_Import, 
                        'Pallet_ID'           => '',
                        'Quantity'            => $value->Quantity,
                        'Inventory'           => $value->Quantity,
                        'Warehouse_Detail_ID' => $value->Warehouse_System_ID,
                        'Status'              => 1,
                        'Type'                => 2,
                        'User_Created'        => Auth::user()->id,
                        'User_Updated'        => Auth::user()->id,
                        'IsDelete'            => 0
                    ];
                    ImportDetail::create($arr);
                }    
            }
            elseif($value->Status == 0)
            {
                $value1  =  ImportDetail::where('IsDelete',0)
                ->where('Box_ID',$value->Box_System_ID)
                ->orderBy('Time_Created','desc')
                ->first();
                // dd($value1);
                if($value1)
                {   


                    ImportDetail::where('ID',$value1->ID)->update([ 
                    'User_Updated'     => Auth::user()->id,
                    'Inventory'        => 0,
                    ]); 

                    $arr1 = [
                        'Export_ID' => '',
                        'Pallet_ID' =>  $value->Pallet_System_ID,
                        'Box_ID'    =>  $value->Box_System_ID,
                        'Materials_ID'=>    $value->Materials_System_ID,
                        'Warehouse_Detail_ID'=> $value->Warehouse_System_ID,
                        'Quantity'  =>  $value->Quantity_System,
                        'Status'    =>  1,
                        'Type'      =>  1,
                        'Time_Export'=>Carbon::now(),
                        'User_Created'     => Auth::user()->id,
                        'User_Updated'     => Auth::user()->id,
                        'IsDelete'         => 0
                    ];
                    ExportDetail::Create($arr1);
                }
            }
        }
        CommandInventory::where('ID',$request->Command_ID)
        ->update([
            'Status'=>1,
            'User_Updated'        => Auth::user()->id,
        ]);
        return true;
    }
    
    public function cancel($request)
    {
        CommandInventory::where('ID',$request->Command_ID)
        ->update([
            'Status'=>2,
            'User_Updated'        => Auth::user()->id,
        ]);
        return true;
    }
    public function add_inventory($request)
    {
        $arr  = [];
        if($request->Materials)
        {
            $data = ImportDetail::where('IsDelete',0)->whereIn('Materials_ID',$request->Materials)
            ->where('Inventory','>',0)
            ->get();
            $mat = MasterMaterials::where('IsDelete',0)->whereIn('ID',$request->Materials)->get('Symbols')->toArray();
            $mat = Arr::flatten($mat);
            $mat = implode('|',$mat);

            foreach($data as $value)
            {
                $arr1 = [
                    'Warehouse_System_ID'   => $value->Warehouse_Detail_ID,
                    'Pallet_System_ID'      => $value->Pallet_ID,
                    'Materials_System_ID'   => $value->Materials_ID,
                    'Box_System_ID'         => $value->Box_ID,
                    'Quantity_System'       => $value->Quantity,
                    'Time_Import_System'    => $value->Time_Import,
                    'Status'                => 0,
                    'Type'                  => 1,
                    'User_Created'          => Auth::user()->id,
                    'User_Updated'          => Auth::user()->id,
                    'IsDelete'              => 0
                ];
                array_push($arr,$arr1);
            }
            
              $command = CommandInventory::create([
                  'Name'             => $request->Name,
                  'Status'           => 0,
                  'Detail'           => $mat,
                  'Type'             => 1,
                  'User_Created'     => Auth::user()->id,
                  'User_Updated'     => Auth::user()->id,
                  'IsDelete'         => 0
              ]);
              foreach($arr as $value)
              {
                  $value['Command_Inventories_ID'] =  $command->ID;
                  InventoryMaterials::create($value);
              }
              return  __('Success'); 
        }
        else if($request->Warehouse)
        {
            $ware = MasterWarehouse::where('IsDelete',0)->whereIn('ID',$request->Warehouse)
            ->get();
            $ware2 = MasterWarehouse::where('IsDelete',0)->whereIn('ID',$request->Warehouse)->get('Symbols')->toArray();
            $ware2 = Arr::flatten($ware2);
            $ware2 = implode('|',$ware2);
            foreach($ware as $value)
            {
                if($value->detail)
                {
                    foreach($value->detail as $value1)
                    {
                        $data = ImportDetail::where('IsDelete',0)->where('Warehouse_Detail_ID',$value1->ID)
                        ->where('Inventory','>',0)
                        ->get(); 
                        foreach($data as $value)
                        {
                            $arr1 = [
                                'Warehouse_System_ID'   => $value->Warehouse_Detail_ID,
                                'Pallet_System_ID'      => $value->Pallet_ID,
                                'Materials_System_ID'   => $value->Materials_ID,
                                'Box_System_ID'         => $value->Box_ID,
                                'Quantity_System'       => $value->Quantity,
                                'Time_Import_System'    => $value->Time_Import,
                                'Status'                => 0,
                                'Type'                  => 2,
                                'User_Created'          => Auth::user()->id,
                                'User_Updated'          => Auth::user()->id,
                                'IsDelete'              => 0
                            ];
                            array_push($arr,$arr1);
                        }
                    } 
                }
            }
            if(count($arr) > 0)
            {
                  $command = CommandInventory::create([
                      'Name'             => $request->Name,
                      'Status'           => 0,
                      'Type'             => 2,
                      'Detail'           => $ware2,
                      'User_Created'     => Auth::user()->id,
                      'User_Updated'     => Auth::user()->id,
                      'IsDelete'         => 0
                  ]);
                  foreach($arr as $value)
                  {
                      $value['Command_Inventories_ID'] =  $command->ID;
                      InventoryMaterials::create($value);
                  }
                  return  __('Success');
            }
            else
            {
                return __('Dont').' '.__('Success');
            }
        }
        elseif($request->Location)
        {
            $data = ImportDetail::where('IsDelete',0)->whereIn('Warehouse_Detail_ID',$request->Location)
            ->where('Inventory','>',0)
            ->get(); 
            $location = MasterWarehouseDetail::where('IsDelete',0)->whereIn('ID',$request->Location)->get('Symbols')->toArray();
            $location = Arr::flatten($location);
            $location = implode('|',$location);
            // dd($location);
            foreach($data as $value)
            {
                $arr1 = [
                    'Warehouse_System_ID'   =>$value->Warehouse_Detail_ID,
                    'Pallet_System_ID'      =>$value->Pallet_ID,
                    'Materials_System_ID'   =>$value->Materials_ID,
                    'Box_System_ID'         =>$value->Box_ID,
                    'Quantity_System'       =>$value->Quantity,
                    'Time_Import_System'    =>$value->Time_Import,
                    'Status'                => 0,
                    'Type'                  => 3,
                    'User_Created'          => Auth::user()->id,
                    'User_Updated'          => Auth::user()->id,
                    'IsDelete'              => 0
                ];
                array_push($arr,$arr1);
            }
            if(count($arr) > 0)
            {
                  $command = CommandInventory::create([
                      'Name'             => $request->Name,
                      'Status'           => 0,
                      'Type'             => 3,
                      'Detail'           => $location,
                      'User_Created'     => Auth::user()->id,
                      'User_Updated'     => Auth::user()->id,
                      'IsDelete'         => 0
                  ]);
                  foreach($arr as $value)
                  {
                      $value['Command_Inventories_ID'] =  $command->ID;
                      InventoryMaterials::create($value);
                  }
                  return  __('Success');
            }
            else
            {
               return __('Dont').' '.__('Success');
            }
        }
        
    }    
}      

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Libraries\UserLibraries;
use Illuminate\Support\Facades\Log;
use App\Models\WarehouseSystem\CommandInventory;
use App\Libraries\WarehouseSystem\InventoryLibraries;
use App\Models\WarehouseSystem\InventoryMaterials;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\WarehouseSystem\ExportDetail;
use App\Models\MasterData\MasterWarehouseDetail;
use App\Models\MasterData\MasterWarehouse;
use Carbon\Carbon;

class InventoriesController extends Controller
{
    public function __construct(
        InventoryLibraries $InventoryLibraries
    ) {
        $this->inventory = $InventoryLibraries;
    }
    public function command_inventory(Request $request)
    {
        $data = CommandInventory::where('IsDelete', 0)->where('Status', 0)->get();
        $arr = [];
        foreach ($data as $value) {
            $details = $value->Detail;
            $details = explode("|",$details);
            if ($value->Type == 2) 
            {
                $warehouse = MasterWarehouse::where('IsDelete',0)->whereIn('Symbols',$details)->get('ID');
                $arr_w = [];
                foreach($warehouse as $value2)
                {
                    array_push($arr_w,$value2->ID);
                }
                $warehouse1 = MasterWarehouseDetail::where('IsDelete',0)->whereIn('Warehouse_ID',$arr_w)->get('Symbols');
            }
            elseif($value->Type == 1)
            {
                $warehouse1 = MasterWarehouseDetail::where('IsDelete',0)->get('Symbols');
            }
            else
            {
                $warehouse1 = MasterWarehouseDetail::where('IsDelete',0)->whereIn('Symbols',$details)->get('Symbols');  
                $details = [];
            }
            $obj = [
                'ID'            => $value->ID,
                'Name'          => $value->Name,
                "User_Created"  => $value->user_created ? $value->user_created->name : "",
                "Time_Created"  => date_format($value->Time_Created,"Y-m-d H:i:s"),
                "Type"          => $value->Type,
                "detail"        => $details,
                "location"      => $warehouse1
            ];
            array_push($arr, $obj);
        }
        if (count($arr) > 0) {
            return response()->json([
                'success' => true,
                'data'      => $arr
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'data'      => []
            ], 200);
        }
    }

    public function detail_inven(Request $request)
    {
        $detail =  InventoryMaterials::where('Inventories_Materials.IsDelete', 0)
        ->join('Master_Warehouse_Detail', 'Inventories_Materials.Warehouse_System_ID', '=', 'Master_Warehouse_Detail.ID')
        ->join('Master_Warehouse', 'Master_Warehouse_Detail.Warehouse_ID', '=', 'Master_Warehouse.ID')
        ->where('Command_Inventories_ID',$request->command_id)
        ->select('Inventories_Materials.ID','Command_Inventories_ID','Warehouse_System_ID','Pallet_System_ID','Materials_System_ID','Box_System_ID','Quantity_System','Box_ID','Quantity','Inventories_Materials.Status','Inventories_Materials.Type','Master_Warehouse_Detail.Symbols as Warehouse_Detail_Symbol','Master_Warehouse.Symbols as Warehouse_Symbol')
        ->get();

        if (count($detail) > 0) {
            return response()->json([
                'success' => true,
                'data' => $detail->groupBy([
                    'Warehouse_Symbol','Warehouse_Detail_Symbol',
                    function($item)
                    {
                        if (!$item['Pallet_System_ID']) {
                            return $item['Pallet_System_ID'].'empty';
                        }
                        return $item['Pallet_System_ID'];
                    }
                ])
            ], 200);
        } 
        else 
        {
            return response()->json([
                'success' => false,
                'data'      => ["message"=>__('Command').' '.__('Inventory').' '.__('Does Not Exist')]
            ], 400);
        }
    }

    public function get_data_box_invent(Request $request)
    {
    	$label = $request->Box_ID;
        $arr_label = explode('[1D]', $label);

        if (count($arr_label) > 12) {
            if ($arr_label[12]) {
                $label_1 = $arr_label[12];
                $label_2 = str_replace('Z', '', $label_1);
                $label_3 = str_replace('[1E][04]', '', $label_2);

                if ($label_3 != '') {
                    $data = ImportDetail::where('IsDelete', 0)
                    ->where('Box_ID', $label_3)
                    ->orderBy('ID', 'desc')
                    ->first();
                    if ($data) 
                    {
                    	return response()->json([
                    		'success'=>true,
                    		'data'=>[
                    			'Box_ID' => $label_3,
                    			'Quantity'=> $data->Quantity
                    		]
                    	],200);
                    }
                    else
                    {
                    	return response()->json([
	                        'success' => false,
	                        'data'      => ['message' => __('Box') . ' ' . __('Does Not Exist').' '.__('In').' '.__('System')]
	                    ], 400);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'data'      => ['message' => __('Box') . ' ' . __('Does Not Exist')]
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'data'      => ['message' => __('Box') . ' ' . __('Does Not Exist')]
                ], 400);
            }
        } 
        else 
        {
            return response()->json([
                'success' => false,
                'data'      => ['message' => __('Box') . ' ' . __('Does Not Exist')]
            ], 400);
        }
    }

    public function update_inventory(Request $request)
    {
        $location = MasterWarehouseDetail::where('IsDelete', 0)->where('Symbols', $request->location)->first();

        if (empty($request->location)) {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Location') . ' ' . __("Can't be empty")]
            ], 400);
        }
        if (!$location) {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Location') . ' ' . __('Does Not Exist')]
                // 'data' => $location
            ], 400);
        }
        if (empty($detail)) {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Dont Scan') . ' ' . __("Box")]
            ], 400);
        }

        foreach($detail as $key => $value)
        {
        	$data = InventoryMaterials::where('IsDelete', 0)
            ->where('Command_Inventories_ID', $request->command_id)
            ->where('Warehouse_System_ID', $location->ID)
            ->when($pallet,function($query,$pallet){
            	return $query->where('Pallet_System_ID',$request->Pallet_ID);
            })
            ->where('Box_System_ID', $value['Box_ID'])
            ->first();
            // dd($data);

	        if ($data) 
	        {
	            if ($data->Quantity_System != $value['Quantity']) {
	                // dd('run1');
	                $dataUpdate = [
	                    'Box_ID'           => $value['Box_ID'],
	                    'Quantity'         => $value['Quantity'],
	                    'Status'           => 1,
	                    // 'User_Created'     => Auth::user()->id,
	                    // 'User_Updated'     => Auth::user()->id,
	                    'IsDelete'         => 0
	                ];
	            } 
	            else 
	            {
	                // dd('run2');
	                $dataUpdate = [
	                    'Box_ID'           => $value['Box_ID'],
	                    'Quantity'         => $value['Quantity'],
	                    'Status'           => 3,
	                    // 'User_Created'     => Auth::user()->id,
	                    // 'User_Updated'     => Auth::user()->id,
	                    'IsDelete'         => 0
	                ];
	            }
	            // dd($dataUpdate);
	            InventoryMaterials::where('ID', $data->ID)->update($dataUpdate);
	        } 
	        else 
	        {
	            $value1  =  ImportDetail::where('IsDelete', 0)
	                ->where('Box_ID', $value['Box_ID'])
	                ->orderBy('ID', 'desc')
	                ->first();

	            if ($value1) {
	                $arr1 = [
	                    'Command_Inventories_ID'=> $request->command_id,
	                    'Warehouse_System_ID'   => $location->ID,
	                    'Pallet_System_ID'      => $request->Pallet_ID,
	                    'Box_ID'           		=> $value['Box_ID'],
	                    'Quantity'         		=> $value['Quantity'],
	                    'Status'                => 2,
	                    'Type'                  => 0,
	                    // 'User_Created'          => Auth::user()->id,
	                    // 'User_Updated'          => Auth::user()->id,
	                    'IsDelete'              => 0
	                ];
	                InventoryMaterials::create($arr1);
	            } 
	    
	        }

        }
        return response()->json([
            'success' => true,
            'data' => ['message' => __('Inventory') . ' ' . __('Success')]
        ], 200); 
    }

    public function success(Request $request)
    {
    	// $request->command_id = 39;
        $data =  InventoryMaterials::where('IsDelete', 0)
            ->where('Command_Inventories_ID', $request->command_id)
            ->get();
        // dd($data);
        if ($data) {
            foreach ($data as $value) 
            {
                if ($value->Status == 1) 
                {
                    $value1  =  ImportDetail::where('IsDelete', 0)
                        ->where('Box_ID', $value->Box_System_ID)
                        ->orderBy('Time_Created', 'desc')
                        ->first();
                    if ($value1) {
                        $arr1 = [
                            'Export_ID' 			=> '',
                            'Pallet_ID' 			=> $value->Pallet_System_ID,
                            'Box_ID'   				=> $value->Box_System_ID,
                            'Materials_ID' 			=> $value->Materials_System_ID,
                            'Warehouse_Detail_ID' 	=> $value->Warehouse_System_ID,
                            'Quantity'  			=> $value->Quantity_System,
                            'Status'    			=> 1,
                            'Type'      			=> 1,
                            'Time_Export' 			=> Carbon::now(),
                            // 'User_Created'     		=> Auth::user()->id,
                            // 'User_Updated'     		=> Auth::user()->id,
                            'IsDelete'         		=> 0
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
                            // 'User_Created'        => Auth::user()->id,
                            // 'User_Updated'        => Auth::user()->id,
                            'IsDelete'            => 0
                        ];
                        ImportDetail::create($arr);
                        ImportDetail::where('ID', $value1->ID)
                            ->update([
                                // 'User_Updated'     => Auth::user()->id,
                                'Inventory'        => 0,
                            ]);
                    }
                } 
                elseif ($value->Status == 2) 
                {
                    $value1  =  ImportDetail::where('IsDelete', 0)
                        ->where('Box_ID', $value->Box_ID)
                        ->orderBy('Time_Created', 'desc')
                        ->first();
                    if ($value1) {
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
                            // 'User_Created'        => Auth::user()->id,
                            // 'User_Updated'        => Auth::user()->id,
                            'IsDelete'            => 0
                        ];
                        ImportDetail::create($arr);
                    }
                } 
                elseif ($value->Status == 0) 
                {
                    $value1  =  ImportDetail::where('IsDelete', 0)
                        ->where('Box_ID', $value->Box_System_ID)
                        ->orderBy('Time_Created', 'desc')
                        ->first();
                    // dd($value1);
                    if ($value1) 
                    {
                        ImportDetail::where('ID', $value1->ID)->update([
                            // 'User_Updated'     => Auth::user()->id,
                            'Inventory'        => 0,
                        ]);

                        $arr1 = [
                            'Export_ID' => '',
                            'Pallet_ID' =>  $value->Pallet_System_ID,
                            'Box_ID'    =>  $value->Box_System_ID,
                            'Materials_ID' =>    $value->Materials_System_ID,
                            'Warehouse_Detail_ID' => $value->Warehouse_System_ID,
                            'Quantity'  =>  $value->Quantity_System,
                            'Status'    =>  1,
                            'Type'      =>  1,
                            'Time_Export' => Carbon::now(),
                            // 'User_Created'     => Auth::user()->id,
                            // 'User_Updated'     => Auth::user()->id,
                            'IsDelete'         => 0
                        ];
                        ExportDetail::Create($arr1);
                    }
                }
            }
            CommandInventory::where('ID', $request->command_id)
                ->update([
                    'Status' => 1,
                    // 'User_Updated'        => Auth::user()->id,
                ]);
            return response()->json([
                'success' => true,
                'data' => ['message' => __('Success').' '.__('Command').' '.__('Inventory')]
            ], 200);
        } 
        else 
        {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Inventory') . ' ' . __('Fail')]
            ], 400);
        }
    }
}

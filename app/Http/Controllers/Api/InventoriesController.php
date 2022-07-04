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
use App\Models\MasterData\MasterMaterials;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class InventoriesController extends Controller
{
    public function __construct(
        InventoryLibraries $InventoryLibraries
    ) {
        $this->inventory = $InventoryLibraries;
    }

    public function command_inventory(Request $request)
    {
        $data = [];
        $commandList = CommandInventory::where('IsDelete', 0)
            ->where('Status', 0)
            ->get();

        foreach ($commandList as $command) {
            $warehouse = [];
            $material  = [];

            switch ($command->Type) {
                case 1:
                    $material  = explode("|", $command->Detail);
                    $warehouse = MasterWarehouseDetail::where('Master_Warehouse_Detail.IsDelete', 0)
                        ->join('Master_Warehouse', 'Master_Warehouse_Detail.Warehouse_ID', '=', 'Master_Warehouse.ID')
                        ->select('Master_Warehouse_Detail.ID', 'Master_Warehouse_Detail.Symbols', 'Master_Warehouse.Name as Master_Warehouse_Name')
                        ->get()
                        ->groupBy('Master_Warehouse_Name')
                        ->toArray();
                    break;

                case 2:
                    $warehouse = MasterWarehouseDetail::where('Master_Warehouse_Detail.IsDelete', 0)
                        ->join('Master_Warehouse', 'Master_Warehouse_Detail.Warehouse_ID', '=', 'Master_Warehouse.ID')
                        ->select('Master_Warehouse_Detail.ID', 'Master_Warehouse_Detail.Symbols', 'Master_Warehouse.Name as Master_Warehouse_Name')
                        ->whereIn('Master_Warehouse.Symbols', explode("|", $command->Detail))
                        ->get()
                        ->groupBy('Master_Warehouse_Name')
                        ->toArray();
                    break;

                case 3:
                    $warehouse = MasterWarehouseDetail::where('Master_Warehouse_Detail.IsDelete', 0)
                        ->join('Master_Warehouse', 'Master_Warehouse_Detail.Warehouse_ID', '=', 'Master_Warehouse.ID')
                        ->select('Master_Warehouse_Detail.ID', 'Master_Warehouse_Detail.Symbols', 'Master_Warehouse.Name as Master_Warehouse_Name')
                        ->whereIn('Master_Warehouse_Detail.Symbols', explode("|", $command->Detail))
                        ->get()
                        ->groupBy('Master_Warehouse_Name')
                        ->toArray();
                    break;
            }

            array_push($data, [
                'ID'            => $command->ID,
                'Name'          => $command->Name,
                "User_Created"  => $command->user_created ? $command->user_created->name : "",
                "Time_Created"  => date_format($command->Time_Created, "Y-m-d H:i:s"),
                "Type"          => $command->Type,
                'Marterial'     => $material,
                'Warehouse'     => $warehouse
            ]);
        }

        return response()->json([
            'success'   => true,
            'data'      => $data
        ], 200);
    }

    public function detail_inven(Request $request)
    {
        $command = CommandInventory::where('IsDelete', 0)
            ->where('ID', $request->command_id)
            ->first();
        $detail  =  InventoryMaterials::where('Inventories_Materials.IsDelete', 0)
            ->where('Command_Inventories_ID', $request->command_id)
            ->get('Warehouse_System_ID')->toArray();
        $detail  = Arr::flatten($detail);

        switch ($command->Type) {
            case 1:
                $warehouse = MasterWarehouseDetail::where('Master_Warehouse_Detail.IsDelete', 0)
                    ->join('Master_Warehouse', 'Master_Warehouse_Detail.Warehouse_ID', '=', 'Master_Warehouse.ID')
                    ->select('Master_Warehouse_Detail.ID')
                    ->get();
                $warehouse = $warehouse->whereNotIn('ID', $detail);

                break;

            case 2:
                $warehouse = MasterWarehouseDetail::where('Master_Warehouse_Detail.IsDelete', 0)
                    ->join('Master_Warehouse', 'Master_Warehouse_Detail.Warehouse_ID', '=', 'Master_Warehouse.ID')
                    ->select('Master_Warehouse_Detail.ID')
                    ->whereIn('Master_Warehouse.Symbols', explode("|", $command->Detail))
                    ->get();
                $warehouse = $warehouse->whereNotIn('ID', $detail);

                break;

            case 3:
                $warehouse = MasterWarehouseDetail::where('Master_Warehouse_Detail.IsDelete', 0)
                    ->join('Master_Warehouse', 'Master_Warehouse_Detail.Warehouse_ID', '=', 'Master_Warehouse.ID')
                    ->select('Master_Warehouse_Detail.ID')
                    ->whereIn('Master_Warehouse_Detail.Symbols', explode("|", $command->Detail))
                    ->get();
                $warehouse = $warehouse->whereNotIn('ID', $detail);
                break;
        }

        $detail2 =  InventoryMaterials::where('Inventories_Materials.IsDelete', 0)
            ->join('Master_Warehouse_Detail', 'Inventories_Materials.Warehouse_System_ID', '=', 'Master_Warehouse_Detail.ID')
            ->join('Master_Warehouse', 'Master_Warehouse_Detail.Warehouse_ID', '=', 'Master_Warehouse.ID')
            ->where('Command_Inventories_ID', $request->command_id)
            ->select('Inventories_Materials.ID', 'Command_Inventories_ID', 'Warehouse_System_ID', 'Pallet_System_ID', 'Materials_System_ID', 'Box_System_ID', 'Quantity_System', 'Box_ID', 'Quantity', 'Inventories_Materials.Status', 'Inventories_Materials.Type', 'Master_Warehouse_Detail.Symbols as Warehouse_Detail_Symbol', 'Master_Warehouse.Symbols as Warehouse_Symbol')
            ->get();

        $warehouse2 = MasterWarehouseDetail::where('Master_Warehouse_Detail.IsDelete', 0)
            ->join('Master_Warehouse', 'Master_Warehouse_Detail.Warehouse_ID', '=', 'Master_Warehouse.ID')
            ->whereIn('Master_Warehouse_Detail.ID', $warehouse)
            ->select('Master_Warehouse_Detail.Symbols as Warehouse_Detail_Symbol', 'Master_Warehouse.Symbols as Warehouse_Symbol')
            ->get();
        $detail3 = Arr::collapse([$detail2, $warehouse2]);
        $detail3 = collect($detail3);
        if (count($detail3) > 0) {
            return response()->json([
                'success' => true,
                'data' => $detail3->groupBy([
                    'Warehouse_Symbol', 'Warehouse_Detail_Symbol',
                    function ($item) {
                        if (!$item['Pallet_System_ID']) {
                            return $item['Pallet_System_ID'] . 'empty';
                        }
                        return $item['Pallet_System_ID'];
                    }
                ])
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data'      => ["message" => __('Command') . ' ' . __('Inventory') . ' ' . __('Does Not Exist')]
            ], 400);
        }
    }

    public function update_inventory(Request $request)
    {
        $detail = $request->detail;
        $pallet = $request->Pallet_ID;
        $dem = 0;
        $location = MasterWarehouseDetail::where('IsDelete', 0)
            ->where('Symbols', $request->location)
            ->first();

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
            ], 400);
        }
        if (empty($detail)) {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Dont Scan') . ' ' . __("Box")]
            ], 400);
        }

        foreach ($detail as $key => $value) {
            $data = InventoryMaterials::where('IsDelete', 0)
                ->where('Command_Inventories_ID', $request->command_id)
                ->where('Warehouse_System_ID', $location->ID)
                ->when($pallet, function ($query, $pallet) {
                    return $query->where('Pallet_System_ID', $pallet);
                })
                ->where('Box_System_ID', $value['Box_ID'])
                ->first();
            $material = MasterMaterials::where('IsDelete', 0)
                ->where('Symbols', $value['material'])
                ->first();

            if ($data) {
                // dd($data);
                if ($data->Quantity_System != $value['Quantity']) {
                    $dataUpdate = [
                        'Box_ID'           => $value['Box_ID'],
                        'Quantity'         => $value['Quantity'],
                        'Status'           => 1,
                        // 'User_Created'     => Auth::user()->id,
                        // 'User_Updated'     => Auth::user()->id,
                        'IsDelete'         => 0
                    ];
                } else {
                    $dataUpdate = [
                        'Box_ID'           => $value['Box_ID'],
                        'Quantity'         => $value['Quantity'],
                        'Status'           => 3,
                        // 'User_Created'     => Auth::user()->id,
                        // 'User_Updated'     => Auth::user()->id,
                        'IsDelete'         => 0
                    ];
                }
                InventoryMaterials::where('ID', $data->ID)->update($dataUpdate);
            } else {
                $data = InventoryMaterials::where('IsDelete', 0)
                    ->where('Command_Inventories_ID', $request->command_id)
                    ->where('Warehouse_System_ID', $location->ID)
                    ->when($pallet, function ($query, $pallet) {
                        return $query->where('Pallet_System_ID', $pallet);
                    })
                    ->where('Box_ID', $value['Box_ID'])
                    ->first();
                if ($data) {
                    $arr1 = [
                        'Quantity' => $value['Quantity'],
                    ];
                    InventoryMaterials::where('ID', $data->ID)->update($arr1);
                } else {
                    $value1  =  ImportDetail::where('IsDelete', 0)
                        ->where('Box_ID', $value['Box_ID'])
                        ->orderBy('ID', 'desc')
                        ->first();

                    if ($value1) {
                        $arr1 = [
                            'Command_Inventories_ID' => $request->command_id,
                            'Warehouse_System_ID'   => $location->ID,
                            'Pallet_System_ID'      => $request->Pallet_ID,
                            'Materials_System_ID'   => $material->ID,
                            'Box_ID'                => $value['Box_ID'],
                            'Quantity'              => $value['Quantity'],
                            'Status'                => 2,
                            'Type'                  => 0,
                            // 'User_Created'          => Auth::user()->id,
                            // 'User_Updated'          => Auth::user()->id,
                            'IsDelete'              => 0
                        ];
                        InventoryMaterials::create($arr1);
                    } else {
                        $arr2 = [
                            'Command_Inventories_ID' => $request->command_id,
                            'Warehouse_System_ID'   => $location->ID,
                            'Pallet_System_ID'      => $request->Pallet_ID,
                            'Materials_System_ID'   => $material->ID,
                            'Time_Import_System'    => Carbon::now(),
                            'Box_ID'                => $value['Box_ID'],
                            'Quantity'              => $value['Quantity'],
                            'Status'                => 2,
                            'Type'                  => 0,
                            // 'User_Created'          => Auth::user()->id,
                            // 'User_Updated'          => Auth::user()->id,
                            'IsDelete'              => 0
                        ];
                        InventoryMaterials::create($arr2);
                    }
                }
            }
            $dem++;
        }

        if ($dem > 0) {
            return response()->json([
                'success' => true,
                'data' => ['message' => __('Inventory') . ' ' . __('Success')]
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'data' => ['message' => __('Inventory') . ' ' . __('Fail')]
            ], 400);
        }
    }

    public function success(Request $request)
    {
        // dd(1);
        $data =  InventoryMaterials::where('IsDelete', 0)
            ->where('Command_Inventories_ID', $request->command_id)
            ->get();
        if ($data) {
            foreach ($data as $value) {
                if ($value->Status == 1) {
                    $value1  =  ImportDetail::where('IsDelete', 0)
                        ->where('Box_ID', $value->Box_System_ID)
                        ->orderBy('Time_Created', 'desc')
                        ->first();
                    if ($value1) {
                        $arr1 = [
                            'Export_ID'             => '',
                            'Pallet_ID'             => $value->Pallet_System_ID,
                            'Box_ID'                => $value->Box_System_ID,
                            'Materials_ID'          => $value->Materials_System_ID,
                            'Warehouse_Detail_ID'   => $value->Warehouse_System_ID,
                            'Quantity'              => $value->Quantity_System,
                            'Status'                => 1,
                            'Type'                  => 1,
                            'Time_Export'           => Carbon::now(),
                            // 'User_Created'     		=> Auth::user()->id,
                            // 'User_Updated'     		=> Auth::user()->id,
                            'IsDelete'              => 0
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
                } elseif ($value->Status == 2) {
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
                    } else {
                        $arr = [
                            'Materials_ID'        => $value->Materials_System_ID,
                            'Box_ID'              => $value->Box_ID,
                            'Time_Import'         => Carbon::now(),
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
                    }
                } elseif ($value->Status == 0) {
                    $value1  =  ImportDetail::where('IsDelete', 0)
                        ->where('Box_ID', $value->Box_System_ID)
                        ->orderBy('Time_Created', 'desc')
                        ->first();
                    if ($value1) {
                        ImportDetail::where('ID', $value1->ID)->update([
                            // 'User_Updated'     => Auth::user()->id,
                            'Inventory'        => 0,
                        ]);

                        $arr1 = [
                            'Export_ID'           => '',
                            'Pallet_ID'           => $value->Pallet_System_ID,
                            'Box_ID'              => $value->Box_System_ID,
                            'Materials_ID'        => $value->Materials_System_ID,
                            'Warehouse_Detail_ID' => $value->Warehouse_System_ID,
                            'Quantity'            => $value->Quantity_System,
                            'Status'              => 1,
                            'Type'                => 1,
                            'Time_Export'         => Carbon::now(),
                            // 'User_Created'         => Auth::user()->id,
                            // 'User_Updated'         => Auth::user()->id,
                            'IsDelete'            => 0
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
                'data' => ['message' => __('Success') . ' ' . __('Command') . ' ' . __('Inventory')]
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Inventory') . ' ' . __('Fail')]
            ], 400);
        }
    }
}

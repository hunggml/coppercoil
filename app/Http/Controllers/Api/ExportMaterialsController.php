<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WarehouseSystem\ExportMaterials;
use App\Models\WarehouseSystem\ExportDetail;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\WarehouseSystem\TransferMaterials;
use App\Models\MasterData\MasterMaterials;
use App\Models\MasterData\MasterUnit;
use App\Models\MasterData\MasterWarehouse;
use App\Models\MasterData\MasterWarehouseDetail;
use App\Models\MasterData\GroupMaterials;
use App\Libraries\WarehouseSystem\ImportLibraries;
use App\Mail\MailNotify;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExportMaterialsController extends Controller
{
    public function __construct(
        ImportLibraries $ImportLibraries,
        MailNotify $MailNotify
    ) {
        $this->send_mail = $MailNotify;
        $this->import = $ImportLibraries;
    }

    public function get_warehouse_and_unit()
    {
        $warehouse  = MasterWarehouse::where('IsDelete', 0)->get();
        $units      = MasterUnit::where('IsDelete', 0)->get();
        $arr        = [];
        $arr1       = [];

        foreach ($units as $value) 
        {
            $arr[$value->Symbols] = $value->ID;
        }

        foreach ($warehouse as $value1) 
        {
            $obj1 = [
                'ID'        => $value1->ID,
                'Name'      => $value1->Name,
                'Symbols'   => $value1->Symbols
            ];
            array_push($arr1, $obj1);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'units'     => $arr,
                'warehouse' => $arr1,
            ]
        ], 200);
    }

    public function get_list_materials_in_warehouse(Request $request)
    {
        $find = MasterWarehouse::where('IsDelete', 0)
                                ->where('ID', $request->Go)
                                ->first();
        $arr  = [];
        $arr2 = [];

        if ($find->detail) 
        {
            foreach ($find->detail as $value) 
            {
                if ($value->inventory) 
                {
                    foreach ($value->inventory->GroupBy('Materials_ID') as $key => $value1) 
                    {
                        if (array_key_exists($key,$arr)) 
                        {
                            $quan = $arr[$key]['Quantity'] += number_format(Collect($value1)->sum('Inventory'), 2, '.', '');
                            $coun = $arr[$key]['Count'] += Count($value1);
                        }
                        else
                        {
                          $arr1 = [
                                'Materials_ID'  => $key,
                                'Materials'     => $value1[0]->materials ? $value1[0]->materials->Symbols : '',
                                'Quantity'      => number_format(Collect($value1)->sum('Inventory'), 2, '.', ''),
                                'Count'         => Count($value1)
                            ]; 
                            $arr[$key] = $arr1; 
                        }
                    }
                }
            }
        }
        if (count($arr) > 0) 
        {
            return response()->json([
                'success' => true,
                'data' => $arr
            ],200);
        } 
        else 
        {
            return response()->json([
                'success' => false,
                'data' => ["message" => __('Dont') . ' ' . __('Stock') . ' ' . __('Materials')]
            ],400);
        }
    }

    // public function export_add(Request $request)
    // {
    //     if (empty($request->Go))
    //     {
    //         return response()->json([
    //             'success' => false,
    //             'data' => ['message' => __('Warehouse') . ' ' . __("Export") . ' ' . __("Can't be empty")]
    //         ], 400);
    //     }

    //     if (empty($request->To))
    //     {
    //         return response()->json([
    //             'success' => false,
    //             'data' => ['message' => __('Warehouse') . ' ' . __("Import") . ' ' . __("Can't be empty")]
    //         ], 400);
    //     }

    //     if (empty($request->Materials_ID))
    //     {
    //         return response()->json([
    //             'success' => false,
    //             'data' => ['message' => __('Materials') . ' ' . __("Can't be empty")]
    //         ], 400);
    //     }

    //     if (empty($request->Unit_ID)) 
    //     {
    //         return response()->json([
    //             'success' => false,
    //             'data' => ['message' => __('Unit') . ' ' . __("Can't be empty")]
    //         ], 400);
    //     }

    //     $warehouse = MasterWarehouse::where('IsDelete', 0)
    //                                 ->where('ID', $request->To)
    //                                 ->first();

    //     $groupMat  = GroupMaterials::where('IsDelete',0)
    //                                 ->where('Group_ID',$warehouse->Group_Materials_ID)
    //                                 ->where('Materials_ID',$request->Materials_ID)
    //                                 ->first();

    //     $mat       = MasterMaterials::where('IsDelete', 0)
    //                                 ->where('ID', $request->Materials_ID)
    //                                 ->first();

    //     $unit      = MasterUnit::where('IsDelete', 0)
    //                                 ->where('ID', $request->Unit_ID)
    //                                 ->first();

    //     $find      = MasterWarehouse::where('IsDelete', 0)
    //                                 ->where('ID', $request->Go)
    //                                 ->first();
    //     $arr       = [];

    //     if ($mat) 
    //     {
    //         if (count($mat->group) == 0 || !$groupMat) {
    //             return response()->json([
    //                 'success'   => false,
    //                 'data'      => ['message' => __('Materials') . ' ' . $mat->Symbols . ' ' . __("Can't be import in warehouse") . ' ' . $warehouse->Symbols]
    //             ],400);
    //         }
    //     }

    //     if ($find->detail) 
    //     {
    //         foreach ($find->detail as $value) 
    //         {
    //             if ($value->inventory) 
    //             {
    //                 foreach ($value->inventory->where('Materials_ID',$request->Materials_ID)->GroupBy('Materials_ID') as $key => $value1) 
    //                 {
    //                     if (array_key_exists($key,$arr)) 
    //                     {
    //                         $quan = $arr[$key]['Quantity'] += number_format(Collect($value1)->sum('Inventory'), 2, '.', '');
    //                         $coun = $arr[$key]['Count'] += Count($value1);
    //                     }
    //                     else
    //                     {
    //                       $arr1 = [
    //                             'Quantity' => number_format(Collect($value1)->sum('Inventory'), 2, '.', ''),
    //                             'Count' => Count($value1)
    //                         ]; 
    //                         $arr[$key] = $arr1; 
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     if ($unit->Type == 2) 
    //     {
    //         if (empty($request->Count)) 
    //         {
    //             return response()->json([
    //                 'success' => false,
    //                 'data' => ['message' => __('Roll Number') . ' ' . __("Can't be empty")]
    //             ], 400);
    //         }

    //         if (!is_numeric($request->Count) || $request->Count <= 0 || is_numeric($request->Count) && is_float($request->Count)) 
    //         {
    //             return response()->json([
    //                 'success' => false,
    //                 'data' => ['message' => __('Roll Number') . ' ' . __('Must be') . ' ' . __('Number') . ' ' . __('Origin') . ' ' . __('And') . ' ' . __('Bigger') . ' ' . "0"]
    //             ], 400);
    //         }

    //         if ($request->Count > $arr[$key]['Count']) 
    //         {
    //         	return response()->json([
    //         		'success'=> false,
    //         		'data' => ['message' => __('Quantity Requested Greater Than Allowed Quantity') . ',' . ' ' . __('Stock') . ': ' . $arr[$key]['Count'] . ' ' . __('Box')]
    //         	],400);
    //         } 
    //     } 
    //     else 
    //     {
    //         if (empty($request->Quantity)) 
    //         {
    //             return response()->json([
    //                 'success' => false,
    //                 'data' => ['message' => __('Quantity') . ' ' . __("Can't be empty")]
    //             ], 400);
    //         }
    //         if (!is_numeric($request->Quantity) || $request->Quantity <= 0) 
    //         {
    //             return response()->json([
    //                 'success' => false,
    //                 'data' => ['message' => __('Quantity') . ' ' . __('Must be') . ' ' . __('Number') . ' ' . __('Real') . ' ' . __('And') . ' ' . __('Bigger') . ' ' . "0"]
    //             ], 400);
    //         }

    //         if ($request->Quantity > $arr[$key]['Quantity']) 
    //         {
    //         	return response()->json([
    //         		'success'=>false,
    //         		'data' => ['message' => __('Quantity Requested Greater Than Allowed Quantity') . ',' . ' ' . __('Stock') . ': ' . $arr[$key]['Quantity'] . ' ' . __('kg')]
    //         	],400);
    //         }
    //     }

    //     $data = ExportMaterials::create([
    //         'Go'            => $request->Go,
    //         'To'            => $request->To,
    //         'Materials_ID'  => $request->Materials_ID,
    //         'Quantity'      => $request->Quantity,
    //         'Count'         => $request->Count,
    //         'Type'          => 1,
    //         'Status'        => 0,
    //         // 'User_Created'  => Auth::user()->id,
    //         // 'User_Updated'  => Auth::user()->id,
    //         'IsDelete'      => 0
    //     ]);
    //     $num    = 1;
    //     $mater  = $data->materials ? $data->materials->Symbols : '';
    //     $Go     = $data->go ? $data->go->Symbols : '';
    //     $To     = $data->to ? $data->to->Symbols : '';
    //     if ($data->go && $data->to) 
    //     {
    //         if ($data->to->Accept == 0) 
    //         {
    //             if ($data->to->Email) 
    //             {
    //                 $this->send_mail->send_mail($data->to->Email, $num, $mater, $Go, $To, $data->Quantity, $data->Count);
    //             }
    //         } 
    //         else 
    //         {
    //             if ($data->go->Accept == 0) 
    //             {
    //                 ExportMaterials::where('IsDelete', 0)
    //                 ->where('ID', $data->ID)
    //                 ->update([
    //                     // 'User_Updated' => Auth::user()->id,
    //                     'Status'       => 1
    //                 ]);
    //             } 
    //             else 
    //             {
    //                 $this->accept((object)[
    //                     'ID' => $data->ID
    //                 ]);
    //             }
    //         }
    //     }
    //     return response()->json([
    //         'success' => true,
    //         'data' => ['message' => __('Create Command Export Success')]
    //     ], 200);
    // }

    public function export_add(Request $request)
    {
        $detail = $request->detail;

        if (empty($request->detail)) 
        {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Dont Scan').' '.__('Box')]
            ], 400);
        }
        if (empty($request->Go))
        {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Warehouse') . ' ' . __("Export") . ' ' . __("Can't be empty")]
            ], 400);
        }

        if (empty($request->To))
        {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Warehouse') . ' ' . __("Import") . ' ' . __("Can't be empty")]
            ], 400);
        }

        foreach($detail as $key => $value)
        {
            if (empty($value['Materials_ID']))
            {
                return response()->json([
                    'success' => false,
                    'data' => ['message' => __('Materials') . ' ' . __("Can't be empty")]
                ], 400);
            }

            if (empty($value['Unit_ID'])) 
            {
                return response()->json([
                    'success' => false,
                    'data' => ['message' => __('Unit') . ' ' . __("Can't be empty")]
                ], 400);
            }

            $warehouse = MasterWarehouse::where('IsDelete', 0)
                                        ->where('ID', $request->To)
                                        ->first();

            $groupMat  = GroupMaterials::where('IsDelete',0)
                                        ->where('Group_ID',$warehouse->Group_Materials_ID)
                                        ->where('Materials_ID',$value['Materials_ID'])
                                        ->first();

            $mat       = MasterMaterials::where('IsDelete', 0)
                                        ->where('ID', $value['Materials_ID'])
                                        ->first();

            $unit      = MasterUnit::where('IsDelete', 0)
                                        ->where('ID', $value['Unit_ID'])
                                        ->first();

            $find      = MasterWarehouse::where('IsDelete', 0)
                                        ->where('ID', $request->Go)
                                        ->first();
            $arr       = [];

            if ($mat) 
            {
                if (count($mat->group) == 0 || !$groupMat) {
                    return response()->json([
                        'success'   => false,
                        'data'      => ['message' => __('Materials') . ' ' . $mat->Symbols . ' ' . __("Can't be import in warehouse") . ' ' . $warehouse->Symbols]
                    ],400);
                }
            }

            if ($find->detail) 
            {
                foreach ($find->detail as $value2) 
                {
                    if ($value2->inventory) 
                    {
                        foreach ($value2->inventory->where('Materials_ID',$value['Materials_ID'])->GroupBy('Materials_ID') as $key => $value1) 
                        {
                            if (array_key_exists($key,$arr)) 
                            {
                                $quan = $arr[$key]['Quantity'] += number_format(Collect($value1)->sum('Inventory'), 2, '.', '');
                                $coun = $arr[$key]['Count'] += Count($value1);
                            }
                            else
                            {
                              $arr1 = [
                                    'Quantity' => number_format(Collect($value1)->sum('Inventory'), 2, '.', ''),
                                    'Count' => Count($value1)
                                ]; 
                                $arr[$key] = $arr1; 
                            }
                        }
                    }
                }
            }

            if ($unit->Type == 2) 
            {
                if (empty($value['Count'])) 
                {
                    return response()->json([
                        'success' => false,
                        'data' => ['message' => __('Roll Number') . ' ' . __("Can't be empty")]
                    ], 400);
                }

                if (!is_numeric($value['Count']) || $value['Count'] <= 0 || is_numeric($value['Count']) && is_float($value['Count'])) 
                {
                    return response()->json([
                        'success' => false,
                        'data' => ['message' => __('Roll Number') . ' ' . __('Must be') . ' ' . __('Number') . ' ' . __('Origin') . ' ' . __('And') . ' ' . __('Bigger') . ' ' . "0"]
                    ], 400);
                }

                if ($value['Count'] > $arr[$key]['Count']) 
                {
                    return response()->json([
                        'success'=> false,
                        'data' => ['message' => __('Quantity Requested Greater Than Allowed Quantity') . ',' . ' ' . __('Stock') . ': ' . $arr[$key]['Count'] . ' ' . __('Box')]
                    ],400);
                } 
            } 
            else 
            {
                if (empty($value['Quantity'])) 
                {
                    return response()->json([
                        'success' => false,
                        'data' => ['message' => __('Quantity') . ' ' . __("Can't be empty")]
                    ], 400);
                }
                if (!is_numeric($value['Quantity']) || $value['Quantity'] <= 0) 
                {
                    return response()->json([
                        'success' => false,
                        'data' => ['message' => __('Quantity') . ' ' . __('Must be') . ' ' . __('Number') . ' ' . __('Real') . ' ' . __('And') . ' ' . __('Bigger') . ' ' . "0"]
                    ], 400);
                }

                if ($value['Quantity'] > $arr[$key]['Quantity']) 
                {
                    return response()->json([
                        'success'=>false,
                        'data' => ['message' => __('Quantity Requested Greater Than Allowed Quantity') . ',' . ' ' . __('Stock') . ': ' . $arr[$key]['Quantity'] . ' ' . __('kg')]
                    ],400);
                }
            }  
        }

        foreach($detail as $value1)
        {
            $unit      = MasterUnit::where('IsDelete', 0)
                                        ->where('ID', $value1['Unit_ID'])
                                        ->first();

            $data = ExportMaterials::create([
                'Go'            => $request->Go,
                'To'            => $request->To,
                'Materials_ID'  => $value1['Materials_ID'],
                'Quantity'      => $unit->Type == 1 ? $value1['Quantity'] : null,
                'Count'         => $unit->Type == 2 ? $value1['Count'] : null,
                'Type'          => 1,
                'Status'        => 0,
                // 'User_Created'  => Auth::user()->id,
                // 'User_Updated'  => Auth::user()->id,
                'IsDelete'      => 0
            ]);
            $num    = 1;
            $mater  = $data->materials ? $data->materials->Symbols : '';
            $Go     = $data->go ? $data->go->Symbols : '';
            $To     = $data->to ? $data->to->Symbols : '';
            if ($data->go && $data->to) 
            {
                if ($data->to->Accept == 0) 
                {
                    if ($data->to->Email) 
                    {
                        $this->send_mail->send_mail($data->to->Email, $num, $mater, $Go, $To, $data->Quantity, $data->Count);
                    }
                } 
                else 
                {
                    if ($data->go->Accept == 0) 
                    {
                        ExportMaterials::where('IsDelete', 0)
                        ->where('ID', $data->ID)
                        ->update([
                            // 'User_Updated' => Auth::user()->id,
                            'Status'       => 1
                        ]);
                    } 
                    else 
                    {
                        $this->accept((object)[
                            'ID' => $data->ID
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => ['message' => __('Create Command Export Success')]
        ], 200);
    }

    public function accept($request)
    {
        // dd($run);
        $data =  ExportMaterials::where('IsDelete', 0)
        ->where('ID', $request->ID)
        ->first();

        if ($data->Status == 1) 
        {
            $request->Materials_ID = $data->Materials_ID;
            $request->warehouse = [];
            if ($data->go) {
                if ($data->go->detail) {
                    foreach ($data->go->detail as $key) {
                        array_push($request->warehouse, $key->ID);
                    }
                }
            }
            $list = $this->import->get_list_with_materials($request);

            $arr = [];
            if ($data->Count) {
                $quan = $data->Count;
            } 
            else 
            {
                $quan = $data->Quantity;
            }

            // dd($quan);
            $dem = 0;
            foreach ($list as $value) 
            {
                if ($quan > 0) {
                    $arr1 = [
                        'Export_ID'     => $data->ID,
                        'Pallet_ID'     => $value->Pallet_ID,
                        'Box_ID'        => $value->Box_ID,
                        'Materials_ID'  => $value->Materials_ID,
                        'Warehouse_Detail_ID' => $value->Warehouse_Detail_ID,
                        'Quantity'      => $value->Quantity,
                        'Status'        => 0,
                        'Type'          => 0,
                        'STT'           => $dem,
                        // 'User_Created'     => Auth::user()->id,
                        // 'User_Updated'     => Auth::user()->id,
                        'IsDelete'         => 0
                    ];

                    array_push($arr, $arr1);
                }
            }
            foreach ($arr as $value) 
            {
                ExportDetail::Create($value);
            }
        } 
        else 
        {
            $num = 2;
            $mater = $data->materials ? $data->materials->Symbols : '';
            $Go = $data->go ? $data->go->Symbols : '';
            $To = $data->to ? $data->to->Symbols : '';
            if ($data->go && $data->to) 
            {
                if ($data->go->Accept == 0) 
                {
                    if ($data->go->Email) 
                    {
                        $this->send_mail->send_mail($data->go->Email, $num, $mater, $Go, $To, $data->Quantity, $data->Count);
                    }
                } 
                else 
                {
                    ExportMaterials::where('IsDelete', 0)
                    ->where('ID', $request->ID)
                    ->update([
                        // 'User_Updated'   => Auth::user()->id,
                        'Status'         => 1
                    ]);

                    $this->accept((object)[
                        'ID' => $request->ID
                    ]);

                    return ExportMaterials::where('IsDelete', 0)
                    ->where('ID', $request->ID)
                    ->update([
                        // 'User_Updated'   => Auth::user()->id,
                        'Status'         => 2
                    ]);
                }
            }
        }
        return ExportMaterials::where('IsDelete', 0)
        ->where('ID', $request->ID)
        ->update([
            // 'User_Updated'   => Auth::user()->id,
            'Status'         => ($data->Status + 1)
        ]);
    }

    public function command_export(Request $request)
    {
        $data = ExportMaterials::where('IsDelete', 0)
                            ->where('Status', 2)
                            ->get();

        $arr = [];
        foreach ($data as $value) 
        {
            $data2 = ExportDetail::where('IsDelete', 0)
                                ->where('Export_ID', $value->ID)
                                ->where('Status',1)
                                ->select('ID','Export_ID','Box_ID','Materials_ID','Warehouse_Detail_ID','Quantity')
                                ->get();

            $data3 = ExportDetail::where('IsDelete', 0)
                                ->where('Export_ID', $value->ID)
                                ->where('Transfer',1)->get();

            $obj = [
                "Name"                  => ($value->Type == 0 ? __('PM') : ($value->Type == 1 ? __('PDA') : __('HT') ) ) .'-'.date_format(date_create($value->Time_Created),"YmdHis"),
                'ID'                    => $value->ID,
                'Materials'             => $value->materials ? $value->materials->Symbols : '',
                'Unit'                  => $value->Quantity ? 'Kg' : 'Box',
                'Go'                    => $value->go ? $value->go->Name : '',
                'To'                    => $value->to ? $value->to->Name : '',
                'Transfer'              => $value->go != $value->to ? true : false,
                'Quantitative_Request'  => $value->Quantity ? floatval($value->Quantity) : $value->Count,
                'Quantitative_Exported' => $value->Quantity ? number_format(Collect($data2)->sum('Quantity'), 2, '.', '') : Count($data2),
                'Quantitative_Transfer' => Count($data3),
                'Boxs_Exported'         => $data2,
            ];
            array_push($arr, $obj);
        }
        if (count($arr) > 0) 
        {
            return response()->json([
                'success' => true,
                'data'    => $arr
            ], 200);
        } 
        else 
        {
            return response()->json([
                'success' => false,
                'data'    => ['message' => __('Does Not Exist') . ' ' . __('Command') . ' ' . __('Export')]
            ], 400);
        }
    }

    public function sucess_command_export(Request $request)
    {
        $command_id = $request->command_id;
        $data = ExportMaterials::where('IsDelete', 0)->where('ID', $command_id)
        ->first();
        if ($data) 
        {
            $a = count(collect($data->detail)->where('Status', 1));
            $b = count(collect($data->detail)->where('Transfer', 1));
            if ($a > $b && $data->Go != $data->To) 
            {
                $num    = $a - $b;
                $mater  = $data->materials ? $data->materials->Symbols : '';
                $Go     = $data->go ? $data->go->Symbols : '';
                $To     = $data->to ? $data->to->Symbols : '';
                $this->send_mail->send_mail2($num, $mater, $Go, $To);
            }
            ExportMaterials::where('IsDelete', 0)->where('ID', $data->ID)
            ->update([
                // 'User_Updated' => Auth::user()->id,
                'Status'       => 3
            ]);
            return response()->json([
                'success' => true,
                'data'    => ['message' => __('Success') . ' ' . __('Command') . ' ' . __('Succes')]
            ], 200);
        } 
        else 
        {
            return response()->json([
                'success' => false,
                'data'    => ['message' => __('Does Not Exist') . ' ' . __('Command') . ' ' . __('Export')]
            ], 400);
        }
    }

    public  function decryption(Request $request)
    {
        $label      = $request->Box_ID;
        $command_id = $request->command_id;
        $arr_label  = explode('[1D]', $label);
        if (count($arr_label) > 12) {
            $label_1 = $arr_label[12];
            $label_2 = str_replace('Z', '', $label_1);
            $label_3 = str_replace('[1E][04]', '', $label_2);

            if ($label_3 != '') {
                $data = ExportMaterials::where('IsDelete', 0)->where('ID', $command_id)->first();
                if ($data) {
                    $data1 = ExportDetail::where('IsDelete', 0)->where('Export_ID', $data->ID)
                    ->where('Box_ID', $label_3)
                    ->where('Type', 0)
                    ->first();

                    $data2 =  ImportDetail::where('IsDelete',0)
                    ->where('Box_ID',$label_3)
                    ->orderBy('ID','desc')
                    ->first();
                    
                    if ($data1 && $data2) 
                    {
                        if ($data1->Status == 0 && $data2->Inventory > 0) 
                        {
                            return response()->json([
                                'success' => true,
                                'data' => [
                                    'Box_ID'    => $label_3,
                                    'Quantity'  => floatval($data1->Quantity),
                                    'Location'  => $data1->location ? $data1->location->Symbols : '',
                                ]
                            ], 200);
                        } 
                        else {
                            return response()->json([
                                'success' => false,
                                'data'    => ['message' => __('Box') . ' ' . __('Was').' '.__('Export')]
                            ], 400);
                        }
                    }
                    else 
                    {
                        return response()->json([
                            'success' => false,
                            'data'    => ['message' => __('Command').' '.__('Export').' '.__("Can't export box")]
                        ], 400);
                    }
                }
                else {
                    return response()->json([
                        'success' => false,
                        'data'    => ['message' => __('Does Not Exist') . ' ' . __('Command') . ' ' . __('Export')]
                    ], 400);
                }
            } 
            else 
            {
                return response()->json([
                    'success' => false,
                    'data'    => ['message' => __('Box') . ' ' . __('Does Not Exist')]
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'data'    => ['message' => __('Box') . ' ' . __('Does Not Exist')]
            ], 400);
        }
    }

    public function export_materials(Request $request)
    {
        $detail   = $request->detail;
        $command  = ExportMaterials::where('IsDelete', 0)->where('ID', $request->command_id)->first();
        $location = MasterWarehouseDetail::where('IsDelete', 0)->where('Symbols', $request->location)->first();
        $arr = [];

        if (empty($detail)) {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Dont Scan') . ' ' . __("Box")]
            ], 400);
        }

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

        if($command)
        {
            foreach($detail as $value)
            {
                array_push($arr,$value['Box_ID']);

                $data1 = ImportDetail::where('IsDelete',0)
                                    ->where('Box_ID',$value['Box_ID'])
                                    ->where('Inventory','>',0)
                                    ->orderBy('ID', 'desc')
                                    ->first();

                if ($data1->Warehouse_Detail_ID != $location->ID) 
                {
                    return response()->json([
                        'success'=>false,
                        'data'=>['message'=> $value['Box_ID'] .' '.__('Not in location').' '.$location->Symbols]
                    ],400);
                }
        	}

            if ($command->Count) 
            {
                $boxs_export = count(collect($command->detail->where('Status',1)));
                $boxs_need = $command->Count - $boxs_export;
                // dd($boxs_need);
                // return response()->json($boxs_need);

                if ($boxs_need >= count($detail)) 
                {
                    $data2 = ExportDetail::where('IsDelete',0)
                                        ->where('Export_ID',$command->ID)
                                        ->whereIn('Box_ID',$arr)
                                        ->update([
                                            'Status' => 1
                                        ]);
                    $data3 =  ImportDetail::where('IsDelete', 0)
                                        ->whereIn('Box_ID', $arr)
                                        ->where('Inventory','>',0)
                                        ->orderBy('ID', 'desc')
                                        ->update([
                                            'Inventory'     => 0,
                                            // 'User_Updated'  => Auth::user()->id
                                        ]); 
                }
                else
                {
                    return response()->json([
                       'success' => false,
                        'data'      => ['message' => __('Just need export') . ' ' . $boxs_need . ' ' . __('Box')]
                    ], 400);
                }
                
            }
            else
            {
                $quan_export = floatval(collect($command->detail->where('Status',1))->sum('Quantity'));
                $quan_need = $command->Quantity - $quan_export;
                $arr_box = [];
                $quan = 0;

                if ($command->Quantity > $quan_export) 
                {
                    if ($quan_need >= collect($detail)->sum('Quantity')) 
                    {
                        $data2 =  ExportDetail::where('IsDelete',0)
                                        ->where('Export_ID',$command->ID)
                                        ->whereIn('Box_ID',$arr)
                                        ->update([
                                            'Status' => 1
                                        ]);
                        $data3 = ImportDetail::where('IsDelete', 0)
                                            ->whereIn('Box_ID', $arr)
                                            ->where('Inventory','>',0)
                                            ->orderBy('ID', 'desc')
                                            ->update([
                                                'Inventory'     => 0,
                                                // 'User_Updated'  => Auth::user()->id
                                            ]); 
                    }
                    else
                    {
                        foreach($detail as $value)
                        {
                            if ($quan_need > $quan) 
                            {
                                array_push($arr_box,$value['Box_ID']);
                                $quan += $value['Quantity'];
                            }
                            else
                            {
                                return response()->json([
                                    'success' => false,
                                    'data'    => ['message' => __('Just need export') . ' ' . count($arr_box) . ' ' . __('Box')]
                                ],400);
                            }
                        }

                        $data4 = ExportDetail::where('IsDelete',0)
                                            ->where('Export_ID',$command->ID)
                                            ->whereIn('Box_ID', $arr_box)
                                            ->update([
                                                'Status' => 1
                                            ]);
                        $data5 = ImportDetail::where('IsDelete', 0)
                                            ->whereIn('Box_ID', $arr_box)
                                            ->where('Inventory','>',0)
                                            ->orderBy('ID', 'desc')
                                            ->update([
                                                'Inventory'     => 0,
                                                // 'User_Updated'  => Auth::user()->id
                                            ]); 
                    }
                }
                else 
                {
                    return response()->json([
                       'success' => false,
                        'data'      => ['message' => __('Quantity has been exported, dont need export more')]
                    ], 400);
                }  
            }
        }
        else
        {
            return response()->json([
                'success' => false,
                'data'      => ['message' => __('Command') . ' ' . __('Does Not Exist')]
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'data'      => ['message' => __('Export') . ' ' . __('Box') . ' ' . __('Success')]
        ], 200);
    }

    // public function data_box_transfer(Request $request)
    // {
    //     $label = $request->Box_ID;
    //     $arr_label = explode('[1D]', $label);
    //     $command_id = $request->command_id;

    //     if (count($arr_label) > 12) {
    //         $label_1 = $arr_label[12];
    //         $label_2 = str_replace('Z', '', $label_1);
    //         $label_3 = str_replace('[1E][04]', '', $label_2);

    //         if ($label_3 != '') {
    //             $data = ExportMaterials::where('IsDelete', 0)->where('ID', $command_id)
    //             ->first();
    //             if ($data) {
    //                 $data1 = ExportDetail::where('IsDelete', 0)->where('Export_ID', $data->ID)
    //                 ->where('Box_ID', $label_3)
    //                 ->where('Status', 1)
    //                 ->where('Type', 0)
    //                 ->first();
    //                 if ($data1) {
    //                     if ($data1->Transfer == 0) {
    //                         return response()->json([
    //                             'success' => true,
    //                             'data' => [
    //                                 'Box_ID'    => $label_3,
    //                                 'Quantity'  => floatval($data1->Quantity),
    //                                 // 'Location'=>$data1->location ? $data1->location->Symbols : '',
    //                             ]
    //                         ], 200);
    //                     } else {
    //                         return response()->json([
    //                             'success' => false,
    //                             'data'      => ['message' => $label_3 . ' ' . __('Was') . ' ' . __('Transfer') . ' ' . __('Warehouse')]
    //                         ], 400);
    //                     }
    //                 }
    //                 else 
    //                 {
    //                     return response()->json([
    //                         'success' => false,
    //                         'data'      => ['message' => $label_3 . ' ' . __('Not in command export')]
    //                     ], 400);
    //                 }
    //             } else {
    //                 return response()->json([
    //                     'success' => false,
    //                     'data'      => ['message' => __('Does Not Exist') . ' ' . __('Command') . ' ' . __('Export')]
    //                 ], 400);
    //             }
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //                 'data'      => ['message' => __('Box') . ' ' . __('Does Not Exist')]
    //             ], 400);
    //         }
    //     } 
    //     else 
    //     {
    //         return response()->json([
    //             'success' => false,
    //             'data'      => ['message' => __('Box') . ' ' . __('Does Not Exist')]
    //         ], 400);
    //     }
    // }

    public function transfer_materials(Request $request)
    {
        $label = $request->detail;
        $dem = 0;
        $location  = MasterWarehouseDetail::where('IsDelete', 0)->where('Symbols', $request->location)->first();
        $command   = ExportMaterials::where('IsDelete',0)->where('ID',$request->command_id)->first();
        $warehouse = MasterWarehouse::where('IsDelete',0)->where('ID');

        if ($command->To != $location->Warehouse_ID) 
        {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Location').' '.__('Not in warehouse import')]
            ], 400);
        }

        if (empty($request->location)) {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Location') . ' ' . __("Can't be empty")]
            ], 400);
        }

        if (empty($label)) {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Dont Scan') . ' ' . __("Box")]
            ], 400);
        }

        if (!$location) {
            return response()->json([
                'success' => false,
                'data' => ['message' => __('Location') . ' ' . __('Does Not Exist')]
            ], 400);
        }

        foreach($label as $val)
        {
            $data1 = ExportDetail::where('IsDelete', 0)
                                ->where('Export_ID', $request->command_id)
                                ->where('Box_ID', $val['Box_ID'])
                                ->where('Status', 1)
                                ->where('Type', 0)
                                ->first();
            if ($data1) {
                if ($data1->Transfer != 0) {
                    return response()->json([
                        'success' => false,
                        'data'      => ['message' => $val['Box_ID'] . ' ' . __('Was') . ' ' . __('Transfer') . ' ' . __('Warehouse')]
                    ], 400);
                }
            }
            else 
            {
                return response()->json([
                    'success' => false,
                    'data'      => ['message' => $val['Box_ID'] . ' ' . __('Not in command export')]
                ], 400);
            }
        }

        foreach ($label as $value) 
        {
            // dd($value);
            $data = ExportMaterials::where('IsDelete', 0)->where('ID', $request->command_id)->first();

            if ($data) {
                $data1 = ExportDetail::where('IsDelete', 0)->where('Export_ID', $data->ID)->where('Box_ID', $value['Box_ID'])->first();
                $data2 = ImportDetail::where('IsDelete', 0)->where('Box_ID', $value['Box_ID'])->orderBy('ID', 'desc')->first();

                $dataSave = ([
                    'Export_ID'              => $data->ID,
                    'Export_Detail_ID'       => $data1->ID,
                    'Box_ID'                 => $value['Box_ID'],
                    'Materials_ID'           => $data1->Materials_ID,
                    'Warehouse_Detail_ID_Go' => $data1->Warehouse_Detail_ID,
                    'Warehouse_Detail_ID_To' => $location->ID,
                    'Quantity'               => $data1->Quantity,
                    'Status'                 => 1,
                    // 'User_Created'           => Auth::user()->id,
                    // 'User_Updated'           => Auth::user()->id,
                    'IsDelete'               => 0
                ]);
                $dataSave1 = ([
                    'Materials_ID'          => $data2->Materials_ID,
                    'Box_ID'                => $value['Box_ID'],
                    'Case_No'               => $data2->Case_No,
                    'Lot_No'                => $data2->Lot_No,
                    'Packing_Date'          => $data2->Packing_Date,
                    'Warehouse_Detail_ID'   => $location->ID,
                    'Quantity'              => $data1->Quantity,
                    'Inventory'             => $data1->Quantity,
                    'Status'                => 1,
                    'Type'                  => 6,
                    'Time_Import'           => Carbon::now(),
                    // 'User_Created'           => Auth::user()->id,
                    // 'User_Updated'           => Auth::user()->id,
                    'IsDelete'              => 0
                ]);

                TransferMaterials::create($dataSave);
                ImportDetail::create($dataSave1);
                ExportDetail::where('IsDelete', 0)
                ->where('ID', $data1->ID)
                ->update([
                    'Transfer' => 1,
                    // 'User_Updated'     => Auth::user()->id,
                ]);
                $dem++;
            }
        }
        if ($dem  > 0) {
            return response()->json([
                'success' => true,
                'data'      => ['message' => __('Transfer') . ' ' . __('Warehouse') . ' ' . __('Success')]
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data'      => ['message' => __('Transfer') . ' ' . __('Warehouse') . ' ' . __('Fail')]
            ], 400);
        }
    }

}

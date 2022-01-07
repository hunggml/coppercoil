<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\WarehouseSystem\ProductReport;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\MasterData\MasterWarehouseDetail;

class CheckInforController
{
    public function check_infor(Request $request)
    {
        $type     = $request->Type;
        $location = $request->Location;
        $box      = $request->Box_ID;

        if ($type == 0) {
            // thông tin pallet
            $data =  ImportDetail::where('IsDelete', 0)
            ->where('Status', '>', 0)
            ->where('Pallet_ID', $request->Pallet_ID)
            ->where('Inventory', '>', 0)
            ->select('ID','Quantity','Materials_ID','Pallet_ID','Warehouse_Detail_ID','Box_ID')
            ->get();
            if (count($data) > 0) {
                $val = [];
                $val['Pallet_ID']   = $data[0]->Pallet_ID;
                $val['Materials']   = $data[0]->materials ? $data[0]->materials->Symbols : '';
                $val['Quantity']    = number_format(collect($data)->sum('Quantity'), 2, '.', '');
                $val['Spec']        = $data[0]->materials ? $data[0]->materials->Spec : '';
                $val['Location']    = $data[0]->location ? $data[0]->location->Symbols : '';
                $val['Count']       = count($data);
                $val['List']        = $data;
                return response()->json([
                    'success' => true,
                    'data'    => $val
                ], 200);
            } 
            else {
                return response()->json([
                    'success' => false,
                    'data'    => ['message' => __('Pallet') . ' ' . __("Don't") . ' ' . __('Stock')]
                ], 400);
            }
        } 
        elseif ($type == 1) {
            // thông tin box
            $label = $request->Box_ID;
            $arr_label = explode('[1D]', $label);
            if (count($arr_label) > 12) {
                if ($arr_label[12]) {
                    $label_1 = $arr_label[12];
                    $label_2 = str_replace('Z', '', $label_1);
                    $label_3 = str_replace('[1E][04]', '', $label_2);

                    if ($label_3 != '') {
                        $data1 = ImportDetail::where('IsDelete', 0)->orderBy('Time_Created', 'desc')
                        ->where('Box_ID', $label_3)
                        ->first();
                        if ($data1) {
                            return response()->json([
                                'success' => true,
                                'data'      => [
                                    'Box_ID'    => $label_3,
                                    'Quantity'  => floatval($data1->Quantity),
                                    'Materials' =>$data1->materials ? $data1->materials->Symbols : '',
                                    'Location'  =>$data1->location ? $data1->location->Symbols : '',                                
                                ]
                            ], 200);
                        } 
                        else {
                            return response()->json([
                                'success' => false,
                                'data'      => ['message' => __('Box') . ' ' . __('Does Not Exist')]
                            ], 400);
                        }
                    } 
                    else {
                        return response()->json([
                            'success' => false,
                            'data'      => ['message' => __('Box') . ' ' . __('Does Not Exist')]
                        ], 400);
                    }
                } 
                else {
                    return response()->json([
                        'success' => false,
                        'data'      => ['message' => __('Box') . ' ' . __('Does Not Exist')]
                    ], 400);
                }
            } 
            else {
                return response()->json([
                    'success' => false,
                    'data'      => ['message' => __('Box') . ' ' . __('Does Not Exist')]
                ], 400);
            }
        } 
        elseif ($type == 2) {
            // thông tin vị trí
            $datas = MasterWarehouseDetail::where('IsDelete', 0)
            ->when($location, function ($q, $location) {
                return $q->where('Symbols', $location);
            })
            ->with([
                'inventory.materials',
                'group_materials',
                'inventory',
                'inventory_null.user_created:id,name,username',
                'inventory_null',
                'inventory.user_created:id,name,username',
                'inventory.user_updated:id,name,username',
            ])
            ->withCount([
                'inventory_null',
                'inventory1'
            ])
            ->get();
            if (count($datas) > 0) {
                foreach ($datas as $value) {
                    if (count($value->inventory) < 0) {
                        return response()->json([
                            'success' => true,
                            'data'    => []
                        ], 200);
                    }
                    $arr = [];
                    $value1 = $value->inventory;
                    foreach ($value1->GroupBy('Pallet_ID') as $key => $value2) {
                        foreach ($value2->GroupBy('Materials_ID') as $key => $value3) {
                            $arr1 = [
                                'Materials' => $value3[0]->materials ? $value3[0]->materials->Symbols : '',
                                'Quantity' => number_format($value3->sum('Inventory'), 2, '.', ''),
                                'Count' => count($value3)
                            ];
                            // $value3[0]['Inventory'] = number_format($value3->sum('Inventory'), 2, '.', '');
                            array_push($arr, $arr1);
                        }
                    }
                    // $value['inventory_nl'] = $arr;
                }
                return response()->json([
                    'success' => true,
                    'data'    => $arr
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'data'    => ['message' => __('Location') . ' ' . __('Does Not Exist')]
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'data'      => ['message' => __('Pallet') . ',' . __('Location') . ',' . __('Box') . ' ' . __('Does Not Exist')]
            ], 400);
        }
    }
}

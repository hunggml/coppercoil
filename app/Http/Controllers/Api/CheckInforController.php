<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\MasterData\MasterWarehouseDetail;


class CheckInforController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function check_infor(Request $request)
    {
        $type     = $request->Type;
        $location = $request->Location;
        $box      = $request->Box_ID;

        if ($type == 0) {
            // thông tin pallet
            $data =  ImportDetail::where('IsDelete', 0)
                ->where('Pallet_ID', $request->Pallet_ID)
                ->where('Inventory', '>', 0) // còn tồn kho
                ->select('ID', 'Quantity', 'Materials_ID', 'Pallet_ID', 'Warehouse_Detail_ID', 'Box_ID')
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
            } else {
                return response()->json([
                    'success' => false,
                    'data'    => ['message' => __('Pallet') . ' ' . __("Don't") . ' ' . __('Stock')]
                ], 400);
            }
        } elseif ($type == 1) {
            // thông tin box
            $label = $request->Box_ID;
            $arr_label = explode('[1D]', $label);
            // return response()->json($arr_label);

            if (count($arr_label) > 12) {
                $label_1 = $arr_label[12];
                $label_2 = str_replace('Z', '', $label_1);
                $label_3 = str_replace('[1E][04]', '', $label_2);
                if ($label_3 != '') {
                    $data1 = ImportDetail::where('IsDelete', 0)
                        ->where('Box_ID', $label_3)
                        ->where('Status', '>', 0) // đã nhập kho
                        ->orderBy('ID', 'desc')
                        ->first();
                    // dd($data1);
                    if ($data1) {
                        if ($data1->Inventory > 0) { //còn tồn kho
                            return response()->json([
                                'success' => true,
                                'data'      => [
                                    'Box_ID'    => $label_3,
                                    'Quantity'  => floatval($data1->Quantity),
                                    'Materials' => $data1->materials ? $data1->materials->Symbols : '',
                                    'Location'  => $data1->location ? $data1->location->Symbols : '',
                                ]
                            ], 200);
                        } else {
                            return response()->json([
                                'success' => false,
                                'data'    => ['message' => __('Box') . ' ' . __('Was') . ' ' . __('Export')]
                            ], 400);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'data'      => ['message' => __('Box') . ' ' . __('Dont import stock')]
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
        } elseif ($type == 2) {
            // thông tin vị trí
            $data = MasterWarehouseDetail::where('IsDelete', 0)
                ->where('Symbols', $location)
                ->orderBy('ID', 'desc')
                ->first();
            if ($data) {
                $arr = [];
                $value = $data->inventory;
                foreach ($value->GroupBy('Materials_ID') as $key => $value1) {
                    $arr1 = [
                        'Materials' => $value1[0]->materials ? $value1[0]->materials->Symbols : '',
                        'Quantity'  => number_format($value1->sum('Inventory'), 2, '.', ''),
                        'Count'     => count($value1)
                    ];
                    array_push($arr, $arr1);
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
                'data'    => ['message' => __('Pallet') . ',' . __('Location') . ',' . __('Box') . ' ' . __('Does Not Exist')]
            ], 400);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\WarehouseSystem\ProductReport;
class ProductivityController 
{
    public function order_detail(Request $request)
    {
        $order_id = $request->order_id;
        $data =  ProductReport::where('IsDelete',0)
        ->where('Order_ID',$order_id)
        ->first();
        // dd($data);
        if($data)
        {
            return response()->json([
				'success' => true,
				'data'	  => [
                    'Order_ID'=> $order_id,
                    'Materials'=>$data->materials ? $data->materials->Symbols : '',
                    'Roll_Number'=>floatval($data->Quantity),
                ]
			],200);
        }
        else
        {
            return response()->json([
				'success' => false,
				'data'	  => ['message' => __('Order').' '.__('Does Not Exist')]
			],400);
        }
    }
    public function update_order(Request $request)
    {
        $order_id = $request->order_id;
        $ok = $request->ok;
        $ng = $request->ng;
        $sum = $ok + $ng;
        $data = ProductReport::where('IsDelete',0)
        ->where('Order_ID',$order_id)
        ->first();
        // dd($data);
        if($data)
        {
            if($ok || $ng)
            {
                if ($sum > $data->Quantity) 
                {
                    return response()->json([
                        'success' => false,
                        'data'    => ['message' =>  __('Sum ng and ok is larger than the order code')]
                    ],400);
                }

                ProductReport::where('IsDelete',0)
                ->where('Order_ID',$order_id)
                ->update([
                    'OK'=> $ok,
                    'NG'=> $ng,
                    // 'User_Updated'     => Auth::user()->id,
                ]);
                return response()->json([
                    'success' => true,
                    'data'	  => ['message' =>  __('Update').' '.__('Order').' '.__('Success')]
                ],200);
            }
            else
            {
                return response()->json([
                    'success' => false,
                    'data'	  => ['message' =>  __('Update').' '.__('Order').' '.__('Fail')]
                ],400);
            }
        }
        else
        {
            return response()->json([
				'success' => false,
				'data'	  => ['message' =>  __('Update').' '.__('Order').' '.__('Fail')]
			],400);
        }
    }

}
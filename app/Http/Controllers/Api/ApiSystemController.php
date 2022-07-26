<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\WarehouseSystem\StockMachine;
use App\Models\WarehouseSystem\ExportDetail;
use App\Models\WarehouseSystem\TransferMaterials;
use App\Models\MasterData\MasterWarehouseDetail;
use App\Models\MasterData\MasterWarehouse;
use App\Models\MasterData\MasterMaterials;
use App\Models\MasterData\MasterProduct;
use App\Models\MasterData\GroupMaterials;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ApiSystemController extends Controller
{
    public function list_bom_and_stock(Request $request)
    {
        
        $product = MasterProduct::where('ID',$request->Product_ID)->first();
        if($product)
        {
            
            if(count($product->boms) > 0)
            {
                $arr = [];
                $check =  false;
                foreach($product->boms as $bom)
                {
                    if($bom->materials)
                    {
                        $mater = $bom->materials;
                        $stock_save    = ImportDetail::where('IsDelete',0)->where('Materials_ID',$bom->Materials_ID)->where('Inventory','>',0)->sum('Inventory');
                        $stock_machine = StockMachine::where('IsDelete',0)->where('Machine_ID',$request->Machine_ID)->where('Materials_ID',$bom->Materials_ID)->sum('Quantity');
                        $mater['save'] = floatval($stock_save);
                        $mater['machine'] = floatval($stock_machine);
                        $mater['dm'] = floatval($bom->Quantity_Materials);
                        $mater['quantity'] = floatval($request->Quantityproduction * $bom->Quantity_Materials);
                        if($stock_save > 0)
                        {
                            $check = true;
                        }
                        array_push($arr,$mater);
                    }
                }
               if($check)
               {
                    return response()->json([
                        'success' => true,
                        'data' => $arr
                    ]);
               }
                
            }
            else
            {
                return response()->json([
                    'success' => false,
                    'data' => []
                ]);
            }
        }
    }
}

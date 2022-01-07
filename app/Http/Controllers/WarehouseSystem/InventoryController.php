<?php

namespace App\Http\Controllers\WarehouseSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\WarehouseSystem\ExportLibraries;
use App\Libraries\MasterData\MasterMaterialsLibraries;
use App\Libraries\MasterData\MasterWarehouseLibraries;
use App\Libraries\WarehouseSystem\TransferLibraries;
use App\Libraries\MasterData\MasterWarehouseDetailLibraries;
use App\Libraries\WarehouseSystem\InventoryLibraries;
class InventoryController extends Controller
{
	

	public function __construct(
        ExportLibraries $ExportLibraries,
        MasterWarehouseLibraries $masterWarehouseLibraries,
        MasterMaterialsLibraries $masterMaterialsLibraries,
        TransferLibraries        $TransferLibraries,
        MasterWarehouseDetailLibraries $masterWarehouseDetailLibraries,
        InventoryLibraries $InventoryLibraries
	)
    {
		$this->middleware('auth');
        $this->export = $ExportLibraries;
        $this->warehouse = $masterWarehouseLibraries;
        $this->materials = $masterMaterialsLibraries;
        $this->transfer = $TransferLibraries;
        $this->warehouse_detail = $masterWarehouseDetailLibraries;
        $this->inventory = $InventoryLibraries;
	}
    
   
    public function inventory(Request $request)
    {   
        $data = $this->inventory->list_inventory($request);
        $data_all = $this->inventory->data_all_inventory($request);
        $warehouse = $this->warehouse->get_all_list_warehouse();
        $list_location = $this->warehouse_detail->get_all_list_warehouse_detail($request);
        $list_materials = $this->materials->get_all_list_materials($request);

        return view('warehouse_system.inventory.command',
        [
            'data'=>$data,
            'data_all'=>$data_all,
            'warehouse'=>$warehouse,
            'list_materials'=>$list_materials,
            'list_location'=>$list_location,
            'request'=>$request
        ]); 
    }
    public function detail(Request $request)
    {   
        $data = $this->inventory->list_inventory_with_command($request);
        $command = $this->inventory->command($request);
        $data_all = $this->inventory->data_all_inventory($request);
        $warehouse = $this->warehouse->get_all_list_warehouse();
        $list_location = $this->warehouse_detail->get_all_list_warehouse_detail($request);
        $list_materials = $this->materials->get_all_list_materials($request);

        // dd($data);
        return view('warehouse_system.inventory.inventory',
        [
            'command'=>$command,
            'data'=>$data,
            'data_all'=>$data_all,
            'warehouse'=>$warehouse,
            'list_materials'=>$list_materials,
            'list_location'=>$list_location,
            'request'=>$request
        ]); 
    }
    public function detail_inven(Request $request)
    {
        
        $data = $this->inventory->detail_inven($request);

        return response()->json([
            'success' => true,
            'data'	  => $data
        ]); 
    }
    public function inventory_add(Request $request)
    {
        
        $data = $this->inventory->add_inventory($request);
        return redirect()->back()->with('success',$data); 
    }
    public function inventory_update(Request $request)
    {
        
        $data = $this->inventory->update_inventory($request);
        return response()->json([
            'data'	  => $data
        ]);
    }

    public function success(Request $request)
    {
        $data = $this->inventory->success($request);
        if ($data) {
            return redirect()->back()->with('success',__('Success')); 
        }
        else
        {
            return redirect()->back()->with('success',__('Fail')); 
        }   
        
    }
    public function cancel(Request $request)
    {
        $data = $this->inventory->cancel($request);
        if ($data) {
            return redirect()->back()->with('success',__('Success')); 
        }
        else
        {
            return redirect()->back()->with('success',__('Fail')); 
        }  
    }
}

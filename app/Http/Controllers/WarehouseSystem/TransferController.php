<?php

namespace App\Http\Controllers\WarehouseSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\WarehouseSystem\ExportLibraries;
use App\Libraries\MasterData\MasterMaterialsLibraries;
use App\Libraries\MasterData\MasterWarehouseLibraries;
use App\Libraries\WarehouseSystem\TransferLibraries;
use App\Libraries\MasterData\MasterWarehouseDetailLibraries;
class TransferController extends Controller
{
    

    public function __construct(
        ExportLibraries $ExportLibraries,
        MasterWarehouseLibraries $masterWarehouseLibraries,
        MasterMaterialsLibraries $masterMaterialsLibraries,
        TransferLibraries        $TransferLibraries,
        MasterWarehouseDetailLibraries $masterWarehouseDetailLibraries
    )
    {
        $this->middleware('auth');
        $this->export = $ExportLibraries;
        $this->warehouse = $masterWarehouseLibraries;
        $this->materials = $masterMaterialsLibraries;
        $this->transfer = $TransferLibraries;
        $this->warehouse_detail = $masterWarehouseDetailLibraries;
    }
    
    public function add_transfer(Request $request)
    {
        $data = $this->transfer->add_transfer($request);
        // $arr = [$val];
        return response()->json([
            'success' => $data ,
            'data'    => $data
        ]);
    }

    public function transfer(Request $request)
    {
        $data = $this->transfer->get_list_transfer($request);
        $list_materials = $this->materials->get_all_list_materials($request);
        $list_location = $this->warehouse_detail->get_all_list_warehouse_detail($request);
        // dd($data);
        return view('warehouse_system.import.transfer',
        [
            'data'=>$data, 
            'list_materials'=>$list_materials,
            'list_location'=>$list_location,
            'request'=>$request
        ]); 
    }

    public function filter_history(Request $request)
    {
        $data = $this->transfer->filter_history($request);
        return response()->json([
            'success' => $data ,
            'data'    => $data
        ]);
    }

    public function get_list_update_location(Request $request)
    {
        $data = $this->transfer->get_list_update_location($request);
        $pallets = $this->transfer->get_all_pallet($request);
        $boxs = $this->transfer->get_boxs_update_location($request);
        $list_materials = $this->materials->get_all_list_materials($request);
        $list_location = $this->warehouse_detail->get_all_list_warehouse_detail($request);
        // dd($data);
        return view('warehouse_system.import.update_location',
        [
            'data'=>$data, 
            'list_materials'=>$list_materials,
            'list_location'=>$list_location,
            'request'=>$request,
            'pallets' => $pallets,
            'boxs' => $boxs
        ]); 

    }

    public function get_list_box(Request $request)
    {
        $data = $this->transfer->get_data_box($request);
        // $arr = [$val];
        return response()->json([
            'data'    => $data
        ]);
    }


    public function get_list_pallet(Request $request)
    {
        $data = $this->transfer->get_data_pallet_update_location($request);
        // $arr = [$val];
        return response()->json([
            'data'    => $data
        ]);
    }

    public function update_location(Request $request)   
    {
        $data = $this->transfer->update_location($request);

        return redirect()->back()->with('success',__('Success'));
    }
}

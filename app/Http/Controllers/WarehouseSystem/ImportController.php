<?php

namespace App\Http\Controllers\WarehouseSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\WarehouseSystem\ImportLibraries;
use App\Libraries\MasterData\MasterMaterialsLibraries;
use App\Libraries\MasterData\MasterSupplierLibraries;
use App\Libraries\MasterData\MasterWarehouseDetailLibraries;
use App\Libraries\MasterData\MasterWarehouseLibraries;
class ImportController extends Controller
{
    

    public function __construct(
        ImportLibraries $ImportLibraries,
        MasterMaterialsLibraries $MasterMaterialsLibraries,
        MasterWarehouseDetailLibraries $masterWarehouseDetailLibraries,
        MasterWarehouseLibraries $masterWarehouseLibraries,
        MasterSupplierLibraries $MasterSupplierLibraries
    )
    {
        $this->middleware('auth');
        $this->command = $ImportLibraries;
        $this->materials = $MasterMaterialsLibraries;
        $this->warehouse_detail = $masterWarehouseDetailLibraries;
        $this->warehouse        = $masterWarehouseLibraries;
        $this->supplier         = $MasterSupplierLibraries; 
    }
    

    public function import(Request $request)
    {
        $data = $this->command->get_list_command_import($request);
        $data_all = $this->command->get_list_all_command();
        $supplier = $this->supplier->get_all_list_supplier();
        return view('warehouse_system.import.import',
        [
            'data'=>$data,
            'data_all'=>$data_all,
            'supplier'=>$supplier,
            'request'=>$request
        ]);  
    }
    public function import_file(Request $request)
    {
        $data = $this->command->import_file_excel($request);
        if(count($data) > 0)
        {
            return redirect()->back()->with('danger',$data); 
        }
        else
        {
            return redirect()->back()->with('success',__('Success')); 
        }
        
    }

    public function detail(Request $request)
    {
        $data = $this->command->detail($request);
        $list_materials = $this->materials->get_all_list_materials($request);
        $data_all = $this->command->detail_all($request);
        $list_location = $this->warehouse_detail->get_all_list_warehouse_detail($request);
        $data_cm = $this->command->get_one_command($request);
        # $this->warehouse_detail
        // dd($data->GroupBy('Materials_ID'));
        return view('warehouse_system.import.detail',
        [
            'data'=>$data, 
            'list_materials'=>$list_materials,
            'data_all'=>$data_all,
            'list_location'=>$list_location,
            'data_cm'=>$data_cm,
            'request'=>$request
        ]);
    }
    
    public function add_stock(Request $request)
    {
        $data = $this->command->add_stock($request);
        // dd($data);
        $list_location = $this->warehouse_detail->get_all_list_warehouse_detail($request);
        return response()->json([
            'success' => true,
            'data'    => $data,
            'list_location'=>$list_location,
        ]);
    }
    public function get_list(Request $request)
    {
        $data = $this->command->get_list($request);
        // $arr = [$val];
        $arr = [];
        $list_location = $this->warehouse_detail->get_all_list_warehouse_detail($request);
        foreach($list_location as $value)
        {
            if($value->Quantity_Packing)
            {
                if( ($value->Quantity_Packing)  > (count($value->inventory) + count($data['List'])) ) 
                {
                 array_push($arr,$value);
                }
            }
            else
            {
                array_push($arr,$value);
            }
           
        }
        return response()->json([
            'data'    => $data,
            'location'=>$arr
        ]);
    }
    public function cancel(Request $request)
    {
        $data = $this->command->cancel($request);
        return redirect()->back()->with('success',__('Success')); 
    }
    public function destroy(Request $request)
    {
        $data = $this->command->destroy($request);
        return redirect()->back()->with('success',__('Success')); 
    }



    public function retype(Request $request)
    {
        $data = $this->command->get_list_retype($request);
        // dd($data);
        $list_materials = $this->materials->get_all_list_materials($request);
        $data_all = $this->command->detail_all($request);
        $list_location = $this->warehouse_detail->get_all_list_warehouse_detail($request);
        // dd($data);
        return view('warehouse_system.import.retype',
        [
            'data'=>$data, 
            'list_materials'=>$list_materials,
            'data_all'=>$data_all,
            'list_location'=>$list_location,
            'request'=>$request
        ]); 
    }

    public function check_infor(Request $request)
    {
        $data = $this->command->check_infor($request);
        // $arr = [$val];
        return response()->json([
            'success' => $data ?  true : false,
            'data'    => $data
        ]);
    }

    public function retype_add(Request $request)
    {
        $data = $this->command->retype_add($request);
        // $arr = [$val];
        return response()->json([
            'success' =>   true ,
            'data'    => $data
        ]);
    }

    public function inventory(Request $request)
    { 
        $data = $this->command->detail_all_list($request);
        $list_materials = $this->materials->get_all_list_materials($request);
        $data_all = $this->command->detail_all_list($request);
        $list_pallet = $this->command->list_pallet();
        $list_location = $this->warehouse_detail->get_all_list_warehouse_detail($request);
        $list_ware =$this->warehouse->get_all_list_warehouse();
        return view('master_data.warehouses.location.table',
        [
            'data'=>$data, 
            'list_materials'=>$list_materials,
            'data_all'=>$data_all,
            'list_pallet'=>$list_pallet,
            'list_ware'=>$list_ware,
            'list_location'=>$list_location,
            'request'=>$request
        ]);
    }
    public function print_label(Request $request)
    {
        $list_box = $this->command->get_all();
        return view('print_label.index',
        [
            'list_box'=>$list_box,
            'request'=>$request
        ]);
    }

    public function detail_box(Request $request)
    {
        $data = $this->command->detail_box($request);
        // $arr = [$val];
        return response()->json([
            'success' =>   true ,
            'data'    => $data
        ]);
    }

    public function add_pallet(Request $request)
    {
        $data = $this->command->add_pallet($request);
        if($data)
        {
            return redirect()->back()->with('success',__('Success')); 
        }
        else
        {
            return redirect()->back()->with('danger',__('Fail')); 
        }
    }
 
}
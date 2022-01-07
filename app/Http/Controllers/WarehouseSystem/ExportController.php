<?php

namespace App\Http\Controllers\WarehouseSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\WarehouseSystem\ExportLibraries;
use App\Libraries\MasterData\MasterMaterialsLibraries;
use App\Libraries\MasterData\MasterWarehouseLibraries; 
class ExportController extends Controller
{
	

	public function __construct(
        ExportLibraries $ExportLibraries,
        MasterWarehouseLibraries $masterWarehouseLibraries,
        MasterMaterialsLibraries $masterMaterialsLibraries
	)
    {
		$this->middleware('auth');
        $this->export = $ExportLibraries;
        $this->warehouse = $masterWarehouseLibraries;
        $this->materials = $masterMaterialsLibraries;
	}
    
    public function export(Request $request)
    {
        $warehouse = $this->warehouse->get_all_list_warehouse();
        $data      = $this->export->get_all_list($request);
        $materials      = $this->materials->get_all_list_materials();

        return view('warehouse_system.export.export',
        [
            'warehouse'=>$warehouse,
            'data'=>$data,
            'materials'=>$materials,
            'request'=>$request
        ]);
    }



    public function export_add(Request $request)
    {
        $data     = $this->export->export_add($request);

        return response()->json([
            'data'	  => $data
        ]); 
    }

    public function cancel(Request $request)
    {
        $data = $this->export->cancel($request);
        return redirect()->back()->with('success',__('Success')); 
    }
    public function accept(Request $request)
    {
        $data = $this->export->accept($request);
        return redirect()->back()->with('success',__('Success')); 
    }
    public function success(Request $request)
    {
        $data = $this->export->success($request);
        return redirect()->back()->with('success',__('Success')); 
    }

    public function detail(Request $request)
    {
        $warehouse = $this->warehouse->get_all_list_warehouse();
        $data_all      = $this->export->get_all_list_detail($request);
        $data      = $this->export->get_filter_list_detail($request);
        $materials      = $this->materials->get_all_list_materials();
        $command      = $this->export->show($request);
        // dd($data);
        return view('warehouse_system.export.detail',
        [
            'warehouse'=>$warehouse,
            'data'=>$data,
            'data_all'=>$data_all,
            'command'=>$command,
            'materials'=>$materials,
            'request'=>$request
        ]);  
    }

    public function ex(Request $request)
    {
        $data      = $this->export->export($request); 
        return redirect()->back()->with('success',$data);
    }
}

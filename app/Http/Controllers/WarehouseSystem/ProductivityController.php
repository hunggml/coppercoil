<?php

namespace App\Http\Controllers\WarehouseSystem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\WarehouseSystem\ExportLibraries;
use App\Libraries\MasterData\MasterMaterialsLibraries;
use App\Libraries\MasterData\MasterWarehouseLibraries;
use App\Libraries\WarehouseSystem\ProductReportLibaries;
use App\Libraries\MasterData\MasterWarehouseDetailLibraries;
use App\Exports\Quality_Warehouses\ProductReport;
use App\Libraries\MasterData\MasterMachineLibraries;
class ProductivityController extends Controller
{
	

	public function __construct(
        ProductReportLibaries $ProductReportLibaries,
        ProductReport    $ProductReport,
        MasterMachineLibraries   $MasterMachineLibraries
	)
    {
		$this->middleware('auth');
        $this->report    = $ProductReportLibaries;
        $this->export    = $ProductReport;
        $this->machine   = $MasterMachineLibraries;
	}
    
    public function productivity(Request $request)
    {
        $data_all = $this->report->get_all_list();
        $data = $this->report->get_all_list_paginate($request);
        $machine = $this->machine->get_all_list_machine();
        return view('warehouse_system.productivity.productivity',
        [
            'data'      =>$data,
            'data_all'  =>$data_all,
            'machine'   =>$machine,
            'request'   =>$request
        ]); 
    }
    public function import_file(Request $request)
    {
        $data = $this->report->import_file_txt($request);
        if(count($data)  == 0)
        {
            return redirect()->route('warehousesystem.productivity')->with('success',__('Success'));
        }
        else
        {
            return redirect()->route('warehousesystem.productivity')->with('danger',$data);
        }
    }

    public function show(Request $request)
    {
        $data = $this->report->show($request);
        
        return response()->json([
            'suc'     => $data ? true : false,
            'data'	  => $data
        ]);
    }
    public function update(Request $request)
    {
        $data = $this->report->update($request);
        if(count($data)  == 0)
        {
            return redirect()->route('warehousesystem.productivity')->with('success',__('Success'));
        }
        else
        {
            return redirect()->route('warehousesystem.productivity')->with('danger',$data);
        }
       
        
        
    }

    public function export_file(Request $request)
    {
        
        $data_all_fill = $this->report->get_all_list_fil($request);
      
        $this->export->export($data_all_fill);
    }
}

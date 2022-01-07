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
use App\Libraries\Report\NxtLibraries;
use App\Exports\Quality_Warehouses\ReportMaterials;
class ReportController extends Controller
{
	

	public function __construct(
        NxtLibraries $NxtLibraries,
        MasterMaterialsLibraries $MasterMaterialsLibraries,
        MasterWarehouseLibraries $MasterWarehouseLibraries,
        ReportMaterials  $ReportMaterials
	)
    {
		$this->middleware('auth');
        $this->report = $NxtLibraries;
        $this->materials = $MasterMaterialsLibraries;
        $this->warehouse = $MasterWarehouseLibraries;
        $this->export = $ReportMaterials;
	}
    
    public function index(Request $request)
    {
        $data = $this->report->get_list_entry($request);
        $list_materials = $this->materials->get_all_list_materials($request);
        $list_warehouse    = $this->warehouse->get_all_list_warehouse();
        return view('warehouse_system.report.index',
        [
            'data'=>$data,
            'list_materials'=>$list_materials, 
            'list_warehouse'=>$list_warehouse,
            'request'=>$request
        ]); 
    }
    public function export_file(Request $request)
    {
        $data = $this->report->get_list_entry($request);
        $this->export->export($data,$request); 
        
    }
}

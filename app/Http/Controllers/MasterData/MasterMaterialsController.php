<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\MasterData\MasterDataImport;
use App\Libraries\MasterData\MasterMaterialsLibraries;
use App\Libraries\MasterData\MasterUnitLibraries;
use App\Libraries\MasterData\MasterSupplierLibraries;
use App\Exports\MasterData\MasterMaterialsExport;


class MasterMaterialsController extends Controller
{
	private $materials;
    private $unit;
    private $supplier;
    private $import;

	public function __construct(
        MasterDataImport $masterDataImport
		,MasterMaterialsLibraries $masterMaterialsLibraries
        ,MasterUnitLibraries $masterUnitLibraries
        ,MasterSupplierLibraries $masterSupplierLibraries
        ,MasterMaterialsExport  $MasterMaterialsExport

	){
        $this->middleware('auth');
        $this->import    = $masterDataImport;
        $this->materials = $masterMaterialsLibraries;
        $this->unit      = $masterUnitLibraries;
        $this->supplier  = $masterSupplierLibraries;
        $this->export    = $MasterMaterialsExport;
	}

    public function index(Request $request)
    {
		$materials  = $this->materials->get_all_list_materials();
		$materialss = $materials;
    	return view('master_data.materials.index',
    	[
			'materials'  => $materials,
			'materialss' => $materialss,
            'request'    => $request
    	]);
    }

    public function filter(Request $request)
    {
		$name       = $request->Name;
		$symbols    = $request->Symbols;
		$materialss = $this->materials->get_all_list_materials();

		$materials  =  $this->materials->filter_materials($request);

    	return view('master_data.materials.index',
    	[
			'materials'  => $materials,
			'materialss' => $materialss,
            'request'    => $request
    	]);
    }

    public function show(Request $request)
    {
        $units     = $this->unit->get_all_list_unit();
        $suppliers = $this->supplier->get_all_list_supplier();
        $materials = $this->materials->filter_materials($request);

    	if (!$request->ID)
    	{
    		$materials = collect([]);
    	}

    	return view('master_data.materials.add_or_update',
    	[
            'materials' => $materials->first(),
            'show'      => true,
            'units'     => $units,
            'suppliers' => $suppliers,
    	]);
    }

    public function add_or_update(Request $request)
    {
		$check = $this->materials->check_materials($request);
		$data  = $this->materials->add_or_update($request);

    	return redirect()->route('masterData.materials')->with('success',$data->status);
    }

    public function import_file_excel(Request $request)
    {
        // get data in file excel
        // dd($request);
        $data  = $this->import->master_materials($request);
        // dd($data);
        if(count($data) > 1)
        {
            return redirect()->back()
            ->with('danger', $data);
        } 
        else
        {
            return redirect()->back()
            ->with('success', __('Success'));
        }
        
    }

    public function export_file(Request $request)
    {
        $data = $this->materials->filter_materials($request);
        // dd($data);
        $this->export->export($data,$request); 

    }

    public function destroy(Request $request)
    {
    	$data = $this->materials->destroy($request);

    	return redirect()->route('masterData.materials')->with('danger',$data);
    }


}

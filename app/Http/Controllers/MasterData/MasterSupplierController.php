<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterSupplierLibraries;
use App\Imports\MasterData\MasterDataImport;
use App\Exports\MasterData\MasterSupplierExport;

class MasterSupplierController extends Controller
{
	private $supplier;

	public function __construct(
		MasterSupplierLibraries $masterSupplierLibraries
        ,MasterSupplierExport  $masterSupplierExport
        ,MasterDataImport $masterDataImport

	){
		$this->middleware('auth');
		$this->supplier = $masterSupplierLibraries;
        $this->export    = $masterSupplierExport;
        $this->import    = $masterDataImport;
		
	}

    public function index(Request $request)
    {
    	$supplier 	= $this->supplier->get_all_list_supplier();
    	$suppliers 	= $supplier;
    	return view('master_data.supplier.index', 
    	[
			'supplier'  => $supplier,
			'suppliers' => $suppliers,
            'request'   => $request
    	]);
    }

    public function filter(Request $request)
    {
		$name      = $request->Name;
		$symbols   = $request->Symbols;
		$suppliers = $this->supplier->get_all_list_supplier();
		
		$supplier  = $suppliers->when($name, function($q, $name) 
		{
			return $q->where('Name', $name);

		})->when($symbols, function($q, $symbols) 
		{
			return $q->where('Symbols', $symbols);
			
		});

    	return view('master_data.supplier.index', 
    	[
			'supplier'  => $supplier,
			'suppliers' => $suppliers,
            'request'   => $request
    	]);
    }

    public function show(Request $request)
    {
    	$supplier = $this->supplier->filter($request);
    	
    	if (!$request->ID) 
    	{
    		$supplier = collect([]);
    		// dd('1');
    	}

    	return view('master_data.supplier.add_or_update', 
    	[
    		'supplier' => $supplier->first(),
    		'show' => true
    	]);
    }

    public function add_or_update(Request $request)
    {
		$check = $this->supplier->check_supplier($request);
		$data  = $this->supplier->add_or_update($request);
    	
    	return redirect()->route('masterData.supplier')->with('success',$data->status);
    }

    public function destroy(Request $request)
    {
    	$data = $this->supplier->destroy($request);

    	return redirect()->route('masterData.supplier')->with('danger',$data);
    }

    public function import_file_excel(Request $request)
    {
        // get data in file excel
        // dd($request);
        $data  = $this->import->master_supplier($request);
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
        $data = $this->supplier->filter($request);
        $this->export->export($data,$request); 
        
    }
}

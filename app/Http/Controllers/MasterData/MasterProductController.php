<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterProductLibraries;
use App\Libraries\MasterData\MasterUnitLibraries;
use App\Libraries\MasterData\MasterMaterialsLibraries;
use App\Exports\MasterData\MasterProductExport;

class MasterProductController extends Controller
{
	private $product;
	private $unit;

	public function __construct(
		MasterProductLibraries $masterProductLibraries
		,MasterUnitLibraries $masterUnitLibraries
        ,MasterProductExport  $masterProductExport
        ,MasterMaterialsLibraries $MasterMaterialsLibraries
	){
        $this->middleware('auth');
        $this->product = $masterProductLibraries;
        $this->unit    = $masterUnitLibraries;
        $this->materials = $MasterMaterialsLibraries;
        $this->export = $masterProductExport;
	}
    
    public function index(Request $request)
    {
    	$product 	= $this->product->get_all_list_product();
    	$products 	= $product;
    	return view('master_data.product.index', 
    	[
			'product'  => $product,
			'products' => $products,
            'request'  => $request
    	]);
    }

    public function get_id_bom_and_materials(Request $request)
    {
        if (!$request->ID) 
        {
            return response()->json([
                'status' => false,
                'data'  => __('Enter').' '.__('ID')
            ]);
        }

        $products = $this->product->filter((object)[
            'ID'        => $request->ID,
            'Name'      => '',
            'Symbols'   => '',
        ])->first();

        if ($products) 
        {
            $boms = $products->bom()->with([
                'or_part:ID,Materials_ID,BOM_ID,Materials_Replace_ID',
                'or_part.materials:ID,Name,Symbols',
            ])->get();

            $dataSend = array();

            foreach ($boms as $key => $bom) 
            {
                if ($bom->or_part) 
                {
                    foreach ($bom->or_part as $key1 => $value) 
                    {
                        // dd($value);
                        if ($value->Materials_Replace_ID == 0) 
                        {
                            if ($value->materials) 
                            {
                                $arr = [
                                    'STT' => $key + 1,
                                    'ID'  => $bom->ID,
                                    'Materials_ID' => $value->Materials_ID,
                                    'Materials_Name' => $value->materials->Name,
                                    'Materials_Symbols' => $value->materials->Symbols,
                                    // 'Materials_Replace_ID' => $value->Materials_Replace_ID
                                ];
                                array_push($dataSend, $arr);
                            }
                        }
                    }
                    
                }
            }

            $keys = array_column($dataSend, 'Materials_ID');

            array_multisort($keys, SORT_ASC, $dataSend);

            return response()->json([
                'status' => true,
                'data'   => $dataSend
            ]);
        }

        return response()->json([
                'status' => false,
                'data'  => __('Enter').' '.__('ID')
            ]);
    }


    public function filter(Request $request)
    {
		$name     = $request->Name;
		$symbols  = $request->Symbols;
		$products = $this->product->get_all_list_product();
		
		$product  = $products->when($name, function($q, $name) 
		{
			return $q->where('Name', $name);

		})->when($symbols, function($q, $symbols) 
		{
			return $q->where('Symbols', $symbols);
			
		});

    	return view('master_data.product.index', 
    	[
			'product'  => $product,
			'products' => $products,
            'request'  => $request
    	]);
    }

    public function show(Request $request)
    {
    	$product = $this->product->filter($request);
    	$units   = $this->unit->get_all_list_unit();
    	$materials = $this->materials->get_all_list_materials();
    	if (!$request->ID) 
    	{
    		$product = collect([]);
    		// dd('1');
    	}

    	return view('master_data.product.add_or_update', 
    	[
			'product' => $product->first(),
			'show'    => true,
			'units'   => $units,
            'materials'=>$materials
    	]);
    }

    public function add_or_update(Request $request)
    {
		$check = $this->product->check_product($request);
		$data  = $this->product->add_or_update($request);
    	
    	return redirect()->route('masterData.product')->with('success',$data->status);
    }

    public function destroy(Request $request)
    {
        $data    = $this->product->destroy($request);
        $arr = [];
        array_push($arr,$data);
    	return redirect()->route('masterData.product')->with('danger',$arr);
    }

    public function import_file(Request $request)
    {
        $data  = $this->product->import_file($request);
        // dd($data);
    	if(count($data)  == 0)
        {
            return redirect()->route('masterData.product')->with('success',__('Success'));
        }
        else
        {
            return redirect()->route('masterData.product')->with('danger',$data);
        }
    	
    }

    public function export_file(Request $request)
    {
        $data = $this->product->filter($request);
        $this->export->export($data,$request); 
        
    }
}

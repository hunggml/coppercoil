<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterGroupMaterialsLibraries;
use App\Libraries\MasterData\GroupMaterialsLibraries;
use App\Libraries\MasterData\MasterMaterialsLibraries;
use App\Libraries\MasterData\MasterUnitLibraries;
use App\Imports\MasterData\MasterDataImport;
use DB;

class MasterGroupMaterialsController extends Controller
{
	private $group_materials;
	private $materials;
	private $group;
	private $unit;
	private $import_file;

	public function __construct(
		MasterGroupMaterialsLibraries $masterGroupMaterialsLibraries
		,MasterMaterialsLibraries $masterMaterialsLibraries
		,GroupMaterialsLibraries $groupMaterialsLibraries
		,MasterUnitLibraries $masterUnitLibraries
		,MasterDataImport $masterDataImport
	){
		$this->middleware('auth');
		$this->group_materials = $masterGroupMaterialsLibraries;
		$this->materials       = $masterMaterialsLibraries;
		$this->group           = $groupMaterialsLibraries;
		$this->unit            = $masterUnitLibraries;
		$this->import_file     = $masterDataImport;
	}
    
    public function index()
    {
		$group  = $this->group_materials->get_all_list_group_materials();
		$groups = $group;
    	return view('master_data.group_materials.index', 
    	[
			'group'  => $group,
			'groups' => $groups
    	]);
    }

    public function filter(Request $request)
    {
		$name    = $request->Name;
		$symbols = $request->Symbols;
		$groups  = $this->group_materials->get_all_list_group_materials();
		
		$group   = $groups->when($name, function($q, $name) 
		{
			return $q->where('Name', $name);

		})->when($symbols, function($q, $symbols) 
		{
			return $q->where('Symbols', $symbols);
			
		});

    	return view('master_data.group_materials.index', 
    	[
			'group'  => $group,
			'groups' => $groups
    	]);
    }

    public function show(Request $request)
    {
		$all_materials = $this->materials->get_all_list_materials(); // all materials
		
		if ($request->ID) 
		{
			$selects       = $this->group->filter((object)
			[
				'ID'           => '',
				'Group_ID'     => $request->ID,
				'Materials_ID' => '',
			]);
		} else
		{
			$selects = collect([]);
		}
		

		// dd($selects);

		// all materials in group
		// $materials_in_group = $this->group->get_all_list_group()
		// ->whereNotIn('Group_ID', [$request->ID])
		// ->pluck('Materials_ID')
		// ->toArray();

		// $materials = $all_materials->whereNotIn('ID', $materials_in_group);
		$materials = $all_materials;

		$group              = $this->group_materials->filter($request);
		// $group              = collect([]);
		$units              = $this->unit->get_all_list_unit();
		// dd($units);
    	
    	if (!$request->ID) 
    	{
    		$group = collect([]);
    		// dd('1');
    	}
    	// dd($group);
    	return view('master_data.group_materials.add_or_update', 
    	[
			'data'      => $group->first(),
			'show'      => true,
			'materials' => $materials,
			'selects'   => $selects,
			'units'     => $units
    	]);
    }

    public function add_or_update(Request $request)
    {
    	// dd($request);
		$check = $this->group_materials->check_group_materials($request);
		$data  = $this->group_materials->add_or_update($request);
		// dd($data, $request);

		if ($request->ID) 
		{
			$id = $request->ID;
		} else
		{
			$id = $data->data->ID;
		}

		$this->group->destroy_all((object)['Group_ID' => $id]);

		if ($request->Group) 
		{
			foreach ($request->Group as $key => $materials) 
			{
				
				$this->group->add_or_update((object)[
					'Group_ID'     => $id,
					'Materials_ID' => $materials
				]);
			}
		}
    	
    	return redirect()->route('masterData.groupMaterials')->with('success', __('Success'));
    }

    public function import_file_excel(Request $request)
    {
    	$data 	= $this->import_file->master_group_materials($request);
    	
    	$errors = $data->error;
    	// dd($data);
    	foreach ($data->data as $key => $value) 
    	{
    		// dd($value);
    		// Lay cac NVL co trong nhom
    		$arrMat 	= array_unique($value['materials']);

    		// Tim kiem all NVL trong nhom su dung Symbols NVL
    		$mat     	= $this->materials->filter_id($arrMat);

    		$idMat 		= $mat->pluck('ID')->toArray();

    		if (count($arrMat) == $mat->count()) 
    		{
    			
    		} else
    		{
    			$symbolsMat = $mat->pluck('Symbols')->toArray();

    			foreach ($arrMat as $arr) 
    			{
    				if (array_search($arr, $symbolsMat) === false) 
    				{
						array_push($errors, __('Materials').' '.$arr.' '.__('Does Not Exist'));
    				}
    			}
    		}

			$units = $this->unit->filter((object)[
				'ID' 		=> '',
				'Name' 		=> '',
				'Symbols' 	=> $value['unit'],
			])->first();

			if ($units) 
			{
				$dataCreate = (object)[
					'Name'		=> $value['name'],
					'Symbols'  	=> $value['name'],
					'Quantity' 	=> $value['quantity'],
					'Unit_ID'  	=> $units->ID
				];
				// Create Or Update Master Group Materials
    			$data = $this->group_materials->add_or_update_file_excel($dataCreate);
    			// Delete All In Table Group_Materials
    			$this->group->destroy_all((object)[
    				'Group_ID' => $data->data->ID
    			]);

	    		foreach ($idMat as $key => $materials) 
				{
					$this->group->add_or_update((object)
					[
						'Group_ID'     => $data->data->ID,
						'Materials_ID' => $materials
					]);
				}
			} else
			{
				array_push($errors, __('Unit').' '.$value['unit'].' '.__('Does Not Exist'));
			}
    	}

    	$sttErr 	= '';
    	$err    	= false;
    	$dataError  = '';

    	
    	if (count($errors) != 0) 
    	{
    		$sttErr 	= __('Error Imports File Excel');
    		$dataError  = implode(',', $errors);
			$err    	= true;
    	}

    	return redirect()->back()
    	->with('success', __('Import By File Excel').' '.__('Success'))
    	->with('danger', $sttErr)
    	->with('table', $err)
    	->with('dataError', $dataError);
    }

    public function destroy(Request $request)
    {
    	$data = $this->group_materials->destroy($request);

    	return redirect()->route('masterData.groupMaterials')->with('danger',$data);
    }
}

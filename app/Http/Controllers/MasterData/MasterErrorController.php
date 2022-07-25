<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterErrorLibraries;

class MasterErrorController extends Controller
{


	public function __construct(
        MasterErrorLibraries $MasterErrorLibraries
	){
		$this->middleware('auth');
        $this->error = $MasterErrorLibraries;
	}
    
    public function index(Request $request)
    {
        $error 	= $this->error->get_all_list_error();
    	$errors 	= $error;
		// dd($unit);
    	return view('master_data.error_ng.index', 
    	[
			'error'  => $error,
			'errors' => $errors,
            'request'    => $request
    	]);
    	
    }
    public function filter(Request $request)
    {
		$name    = $request->Name;
		$symbols = $request->Symbols;
		$errors   = $this->error->get_all_list_error();
		
		$error    = $errors->when($name, function($q, $name) 
		{
			return $q->where('Name', $name);

		});

    	return view('master_data.error_ng.index', 
    	[
			'error'  => $error,
			'errors' => $errors,
            'request' => $request
    	]);
    }

    public function show(Request $request)
    {
    	$error = $this->error->filter($request);
    	// dd($unit->first());
    	if (!$request->ID) 
    	{
    		$error = collect([]);
    		// dd('1');
    	}

    	return view('master_data.error_ng.add_or_update', 
    	[
    		'error' => $error->first(),
    		'show' => true
    	]);
    }
    
   
    public function add_or_update(Request $request)
    {
		// dd($request);
		$check = $this->error->check_error($request);
		$data  = $this->error->add_or_update($request);
    	
    	return redirect()->route('masterData.error')->with('success',$data->status);
    }
}

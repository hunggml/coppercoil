<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class MasterErrorController extends Controller
{


	public function __construct(
	){
		$this->middleware('auth');
	}
    
    public function index(Request $request)
    {
    	return view('master_data.error_ng.index');
    }

    
    public function show(Request $request)
    {
    	return view('master_data.error_ng.add_or_update');
    }

}

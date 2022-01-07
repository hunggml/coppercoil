<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Imports\MasterData\MasterDataImport;

class HomeController extends Controller
{
    private $im;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MasterDataImport $masterDataImport)
    {
        // $this->middleware('auth');
        $this->im = $masterDataImport;
    }

    /**
     * Show the application welcome.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome()
    {
        return view('welcome');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function change_language($language)
    {
        Session::put('language', $language);

        return redirect()->back();
    }

    public function view_label_sti()
    {
        $data = array();
        return view('sti.label', [
            'data'  => array(),
            'label' => ''
        ]);
    }

    public function print_label_sti(Request $request)
    {
        $data = $this->im->sti_label($request);

        return view('sti.label', [
            'data'  => $data[0],
            'label' => $data[1]
        ]);
    }
}

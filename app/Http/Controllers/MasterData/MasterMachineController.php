<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\MasterData\MasterMachineLibraries;

class MasterMachineController extends Controller
{
    private $machine;

    public function __construct(
        MasterMachineLibraries $masterMachineLibraries

    ) {
        $this->middleware('auth');
        $this->machine = $masterMachineLibraries;
    }

    public function index(Request $request)
    {
        $machine     = $this->machine->get_all_list_machine();
        $machines     = $machine;
        return view(
            'master_data.machine.index',
            [
                'machiness'  => $machine,
                'machines' => $machines,
                'request'    => $request
            ]
        );
    }

    public function filter(Request $request)
    {
        $name    = $request->Name;
        $symbols = $request->Symbols;
        $machines  = $this->machine->get_all_list_machine();

        $machine    = $machines->when($name, function ($q, $name) {
            return $q->where('Name', $name);
        })->when($symbols, function ($q, $symbols) {
            return $q->where('Symbols', $symbols);
        });

        return view(
            'master_data.machine.index',
            [
                'machiness'  => $machines,
                'machines' => $machine,
                'request' => $request
            ]
        );
    }

    public function show(Request $request)
    {
        $machine = $this->machine->filter($request);
        // dd($machine->first());
        if (!$request->ID) {
            $machine = collect([]);
            // dd('1');
        }

        return view(
            'master_data.machine.add_or_update',
            [
                'machines' => $machine->first(),
                'show' => true
            ]
        );
    }

    public function add_or_update(Request $request)
    {
        // dd($request);
        $check = $this->machine->check_machine($request);
        $data  = $this->machine->add_or_update($request);

        return redirect()->route('masterData.machine')->with('success', $data->status);
    }

    public function destroy(Request $request)
    {
        $data = $this->machine->destroy($request);

        return redirect()->route('masterData.machine')->with('danger', $data);
    }
}

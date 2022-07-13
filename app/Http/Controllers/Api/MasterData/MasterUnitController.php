<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\MasterUnit;
use App\Models\MasterData\History\UnitHistory;

class MasterUnitController extends Controller
{
    public function index(Request $request)
    {
        $name      = $request->name;
        $symbols = $request->symbols;
        $masterUnit = MasterUnit::where('IsDelete', 0)
            ->when($name, function ($query, $name) {
                return $query->where('Name', $name);
            })->when($symbols, function ($query, $symbols) {
                return $query->where('Symbols', $symbols);
            })
            ->with([
                'user_created:id,username',
                'user_updated:id,username'
            ])
            ->orderBy('Time_Updated', 'desc')
            ->paginate($request->length);

        return response()->json([
            'recordsTotal' => $masterUnit->total(),
            'recordsFiltered' => $masterUnit->total(),
            'data' => $masterUnit->toArray()['data']
        ]);
    }
}

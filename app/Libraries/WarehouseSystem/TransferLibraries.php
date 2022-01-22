<?php

namespace App\Libraries\WarehouseSystem;

use Illuminate\Validation\Rule;
use App\Models\WarehouseSystem\CommandImport;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\WarehouseSystem\ExportDetail;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Models\MasterData\MasterMaterials;
use App\Models\MasterData\MasterWarehouseDetail;
use App\Models\MasterData\MasterWarehouse;
use Auth;
use Carbon\Carbon;
use App\Models\WarehouseSystem\TransferMaterials;

class TransferLibraries
{

    private function read_file($request)
    {
        try {
            $file     = request()->file('fileImport');
            $name     = $file->getClientOriginalName();
            $arr      = explode('.', $name);
            $fileName = strtolower(end($arr));
            // dd($file, $name, $arr, $fileName);
            if ($fileName != 'xlsx' && $fileName != 'xls') {
                return redirect()->back();
            } else if ($fileName == 'xls') {
                $reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else if ($fileName == 'xlsx') {
                $reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            try {
                $spreadsheet = $reader->load($file);
                $data        = $spreadsheet->getActiveSheet()->toArray();

                return $data;
            } catch (\Exception $e) {
                return ['danger' => __('Select The Standard ".xlsx" Or ".xls" File')];
            }
        } catch (\Exception $e) {
            return ['danger' => __('Error Something')];
        }
    }

    public function add_transfer($request)
    {
        // dd($request);
        $ex = ExportDetail::where('ID', $request->Detail_ID)->where('IsDelete', 0)
            ->first();

        // dd($ex);
        if ($ex) {
            $im = ImportDetail::where('Box_ID', $ex->Box_ID)
                ->where('Materials_ID', $ex->Materials_ID)
                ->where('IsDelete', 0)
                ->orderBy('ID', 'desc')
                ->first();
            if ($im->Inventory <= 0) {
                $dataSave = ([
                    'Export_ID'        => $ex->Export_ID,
                    'Export_Detail_ID' => $ex->ID,
                    'Box_ID'           => $ex->Box_ID,
                    'Materials_ID'     => $ex->Materials_ID,
                    'Warehouse_Detail_ID_Go' => $ex->Warehouse_Detail_ID,
                    'Warehouse_Detail_ID_To' => $request->To,
                    'Quantity'         => $ex->Quantity,
                    'Status'           => 1,
                    'User_Created'     => Auth::user()->id,
                    'User_Updated'     => Auth::user()->id,
                    'IsDelete'         => 0
                ]);
                $dataSave1 = ([
                    'Materials_ID'    => $ex->Materials_ID,
                    'Box_ID'          => $ex->Box_ID,
                    'Case_No'         => $im->Case_No,
                    'Lot_No'          => $im->Lot_No,
                    'Quantity'        => $ex->Quantity,
                    'Packing_Date'    => $im->Packing_Date,
                    'Warehouse_Detail_ID' => $request->To,
                    'Quantity'        => $ex->Quantity,
                    'Inventory'       => $ex->Quantity,
                    'Status'          => 1,
                    'Type'            => 6,
                    'Time_Import'     => Carbon::now(),
                    'User_Created'    => Auth::user()->id,
                    'User_Updated'    => Auth::user()->id,
                    'IsDelete'        => 0
                ]);

                TransferMaterials::create($dataSave);
                ImportDetail::create($dataSave1);
                ExportDetail::where('IsDelete', 0)
                    ->where('ID', $request->Detail_ID)
                    ->update([
                        'Transfer' => 1,
                        'User_Updated'     => Auth::user()->id
                    ]);

                return __('Success');
            } else {
                return __('Dont Success');
            }
        } else {
            return __('Dont Success');
        }
    }

    public function get_list_transfer($request)
    {
        $materials = $request->Materials_ID;
        $Box_ID = trim($request->Box_ID);
        $Status = 1;
        $from = $request->from;
        $to   = $request->to;
        $Go_to   = $request->Go_to;
        $Lo_to   = $request->Lo_to;
        $data = TransferMaterials::where('IsDelete', 0)
            ->when($materials, function ($query, $materials) {
                return $query->where('Materials_ID', $materials);
            })
            ->when($Box_ID, function ($query, $Box_ID) {
                return $query->where('Box_ID', $Box_ID);
            })
            ->when($Status, function ($query, $Status) {
                return $query->where('Status', $Status);
            })
            ->when($from, function ($query, $from) {
                return $query->where('Time_Created', '>=',  Carbon::create($from)->startOfDay()->toDateTimeString());
            })
            ->when($to, function ($query, $to) {
                return $query->where('Time_Created', '<=', Carbon::create($to)->endOfDay()->toDateTimeString());
            })
            ->when($Go_to, function ($query, $Go_to) {
                return $query->where('Warehouse_Detail_ID_Go', $Go_to);
            })
            ->when($Lo_to, function ($query, $Lo_to) {
                return $query->where('Warehouse_Detail_ID_To', $Lo_to);
            })
            ->orderBy('Time_Created', 'desc')
            ->paginate(10);
        return $data;
    }

    // public function get_location($request)
    // {
        
    // }


    public function get_all_pallet($request)
    {

        $data =  ImportDetail::where('IsDelete', 0)
            ->where('Status', '!=', 2)
            ->where('Inventory', '>', 0)
            ->whereNotNull('Pallet_ID')
            ->with('materials', 'location')
            ->get();

        return $data;
    }

    public function get_data_pallet_update_location($request)
    {

        $data =  ImportDetail::where('IsDelete', 0)
            ->where('Status', '!=', 2)
            ->where('Pallet_ID', $request->Pallet_ID)
            ->where('Inventory', '>', 0)
            ->with('materials', 'location')
            ->get();
        if (count($data) > 0) {
            $val = [];
            $val['Pallet_ID'] = $data[0]->Pallet_ID;
            $val['Status']    = $data[0]->Status;
            $val['Materials'] = $data[0]->materials ? $data[0]->materials->Symbols : '';
            $val['Materials_ID'] = $data[0]->materials ? $data[0]->materials->ID : '';
            $val['Wire_Type'] = $data[0]->materials ? $data[0]->materials->Wire_Type : '';
            $val['Spec'] = $data[0]->materials ? $data[0]->materials->Spec   : '';
            $val['Quantity'] = number_format(collect($data)->sum('Quantity'), 2, '.', '');
            $val['Count'] = count($data);
            $val['List'] = $data;
            return $val;
        }
    }

    public function get_boxs_update_location($request)
    {
        $data =  ImportDetail::where('IsDelete', 0)
            ->where('Status', '!=', 2)
            ->where('Inventory', '>', 0)
            ->with('materials', 'location')
            ->get();

        return $data;
    }

    public function get_data_box($request)
    {
        $data =  ImportDetail::where('IsDelete', 0)
            ->where('Box_ID', $request->Box_ID)
            ->where('Inventory', '>', 0)
            ->with(['materials', 'location'])
            ->orderBy('ID', 'desc')
            ->first();

        return $data;
    }

    public function update_location($request)
    {
        if ($request->Box_ID) {
            // dd('run1');
            $data =  ImportDetail::where('IsDelete', 0)
                ->where('Box_ID', $request->Box_ID)
                ->where('Status', '!=', 2)
                ->orderBy('ID', 'desc')
                ->with('materials', 'location')
                ->first();
            if ($data) {
                $arr1 = [
                    'Export_ID' => '',
                    'Pallet_ID' =>  '',
                    'Box_ID'    =>  $data->Box_ID,
                    'Materials_ID' =>    $data->Materials_ID,
                    'Warehouse_Detail_ID' => $data->Warehouse_Detail_ID,
                    'Quantity'  =>  $data->Quantity,
                    'Status'    =>  1,
                    'Type'      =>  2,
                    'Time_Export' => Carbon::now(),
                    'User_Created'     => Auth::user()->id,
                    'User_Updated'     => Auth::user()->id,
                    'IsDelete'         => 0
                ];
                ExportDetail::Create($arr1);

                $data->update([
                    'Inventory' => 0,
                    'User_Updated' => Auth::user()->id
                ]);

                $arr = [
                    'Materials_ID'        => $data->Materials_ID,
                    'Box_ID'              => $data->Box_ID,
                    'Case_No'             => $data->Case_No,
                    'Lot_No'              => $data->Lot_No,
                    'Time_Import'         => $data->Time_Import,
                    'Pallet_ID'           => '',
                    'Quantity'            => $data->Quantity,
                    'Inventory'           => $data->Quantity,
                    'Warehouse_Detail_ID' => $request->Warehouse_Detail_ID_box,
                    'Status'              => 1,
                    'Type'                => 3,
                    'User_Created'        => Auth::user()->id,
                    'User_Updated'        => Auth::user()->id,
                    'IsDelete'            => 0
                ];
                ImportDetail::create($arr);

                $dataSave = ([
                    'Export_ID'        => '',
                    'Export_Detail_ID' => '',
                    'Pallet_ID' => '',
                    'Box_ID' => $data->Box_ID,
                    'Materials_ID' => $data->Materials_ID,
                    'Warehouse_Detail_ID_Go' => $data->Warehouse_Detail_ID,
                    'Warehouse_Detail_ID_To' => $request->Warehouse_Detail_ID_box,
                    'Quantity' => $data->Quantity,
                    'Status' => 2,
                    'User_Created'     => Auth::user()->id,
                    'User_Updated'     => Auth::user()->id,
                    'IsDelete'         => 0
                ]);

                TransferMaterials::create($dataSave);
            }
        } 
        else {

            $data =  ImportDetail::where('IsDelete', 0)
                ->where('Pallet_ID', $request->Pallet_ID)
                ->where('Status', '!=', 2)
                ->orderBy('ID', 'desc')
                ->with('materials', 'location')
                ->get();
            // dd($request->Warehouse_Detail_ID_pallet);

            if ($data) {
                foreach ($data as $value1) {
                    // dd($value1);

                    $arr1 = [
                        'Export_ID' => '',
                        'Box_ID'    =>  $value1->Box_ID,
                        'Pallet_ID' =>  $value1->Pallet_ID,
                        'Materials_ID' =>    $value1->Materials_ID,
                        'Warehouse_Detail_ID' => $value1->Warehouse_Detail_ID,
                        'Quantity'  =>  $value1->Quantity,
                        'Status'    =>  1,
                        'Type'      =>  2,
                        'Time_Export' => Carbon::now(),
                        'User_Created'     => Auth::user()->id,
                        'User_Updated'     => Auth::user()->id,
                        'IsDelete'         => 0
                    ];
                    ExportDetail::Create($arr1);

                    $value1->update([
                        'Inventory' => 0,
                        'User_Updated' => Auth::user()->id
                    ]);

                    $arr = [
                        'Materials_ID'        => $value1->Materials_ID,
                        'Box_ID'              => $value1->Box_ID,
                        'Pallet_ID'           => $value1->Pallet_ID,
                        'Case_No'             => $value1->Case_No,
                        'Lot_No'              => $value1->Lot_No,
                        'Time_Import'         => $value1->Time_Import,
                        'Quantity'            => $value1->Quantity,
                        'Inventory'           => $value1->Quantity,
                        'Warehouse_Detail_ID' => $request->Warehouse_Detail_ID_pallet,
                        'Status'              => 1,
                        'Type'                => 3,
                        'User_Created'        => Auth::user()->id,
                        'User_Updated'        => Auth::user()->id,
                        'IsDelete'            => 0
                    ];
                    ImportDetail::create($arr);

                    $dataSave = ([
                        'Export_ID'        => '',
                        'Export_Detail_ID' => '',
                        'Pallet_ID' => $value1->Pallet_ID,
                        'Box_ID' => $value1->Box_ID,
                        'Materials_ID' => $value1->Materials_ID,
                        'Warehouse_Detail_ID_Go' => $value1->Warehouse_Detail_ID,
                        'Warehouse_Detail_ID_To' => $request->Warehouse_Detail_ID_pallet,
                        'Quantity' => $value1->Quantity,
                        'Status' => 2,
                        'User_Created'     => Auth::user()->id,
                        'User_Updated'     => Auth::user()->id,
                        'IsDelete'         => 0
                    ]);

                    TransferMaterials::create($dataSave);
                }
            }
        }
    }

    public function get_list_update_location($request)
    {
        $materials = $request->Materials_ID;
        $Status = 2;
        $Pallet = $request->Pallet_ID;
        $Box = $request->Box_ID;
        $from = $request->from;
        $to   = $request->to;
        return  TransferMaterials::where('IsDelete', 0)
            ->when($materials, function ($query, $materials) {
                return $query->where('Materials_ID', $materials);
            })
            ->when($Status, function ($query, $Status) {
                return $query->where('Status', $Status);
            })
            ->when($Pallet, function ($query, $Pallet) {
                return $query->where('Pallet_ID', $Pallet);
            })
            ->when($Box, function ($query, $Box) {
                return $query->where('Box_ID', $Box);
            })
            ->when($from, function ($query, $from) {
                return $query->where('Time_Created', '>=', Carbon::create($from)->startOfDay()->toDateTimeString());
            })
            ->when($to, function ($query, $to) {
                return $query->where('Time_Created', '<=', Carbon::create($to)->endOfDay()->toDateTimeString());
            })->orderBy('ID', 'DESC')

            ->paginate(10);
        // dd($data);


    }

    public function filter_history($request)
    {
        $id      = $request->ID;
        $name    = $request->Name;
        $symbols = $request->Symbols;

        $datas = MasterWarehouseDetail::where('IsDelete', 0)
            ->when($id, function ($q, $id) {
                return $q->where('ID', $id);
            })
            ->when($name, function ($q, $name) {
                return $q->where('Name', $name);
            })
            ->when($symbols, function ($q, $symbols) {
                return $q->where('Symbols', $symbols);
            })
            ->get();
        $arr = [];
        foreach ($datas as $value) {
            $data = ImportDetail::where('IsDelete', 0)->where('Warehouse_Detail_ID', $value->ID)
                ->with('materials', 'location')
                ->get();
            // foreach($data as $value1)
            // {
            //     array_push($arr,$value1);
            // }
            foreach ($data->GroupBy('Pallet_ID') as $key => $value2) {

                foreach ($value2->GroupBy('Materials_ID') as $key => $value3) {
                    foreach ($value3->GroupBy('Type') as $key => $value4) {
                        $value4[0]['Quantity'] = number_format($value4->sum('Quantity'), 2, '.', '');
                        $value4[0]['Count'] = count($value4);
                        // $box = $value4[0]['Count'] > 1 ? '' : $value4[0];
                        if ($value4[0]['Count'] > 1) {
                            $value4[0]['Box_ID'] = '';
                        }
                        // else
                        // {
                        //     $value4[0]['Box_ID']= $value4[0]['Box_ID'];

                        // }
                        array_push($arr, $value4[0]);
                    }
                }
            }

            $data1 = ExportDetail::where('IsDelete', 0)->where('Warehouse_Detail_ID', $value->ID)
                ->where('Status', 1)
                ->with('materials', 'location')
                ->get();
                // dd($data1);
            foreach ($data1 as $value2) {
                if ($value2->Type == 0) {
                    $value2->Type = 4;
                    $value2->Count = 1;
                } 
                if ($value2->Type == 2) {
                    $value2->Type = 5;
                    $value2->Count = 1;
                }
                if ($value2->Type == 1) {
                    $value2->Type = 7;
                    $value2->Count = 1;
                }
                array_push($arr, $value2);
            }
        }
        // dd($arr);
        return $arr;
    }
}

<?php

namespace App\Libraries\WarehouseSystem;

use Illuminate\Validation\Rule;
use Validator;
use App\Models\WarehouseSystem\ExportMaterials;
use App\Models\WarehouseSystem\StockMachine;
use App\Models\WarehouseSystem\ExportDetail;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Models\MasterData\MasterMaterials;
use Auth;
use Carbon\Carbon;
use App\Libraries\WarehouseSystem\ImportLibraries;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\MasterData\MasterWarehouseDetail;
use App\Mail\MailNotify;

class ExportLibraries
{
    public function __construct(
        ImportLibraries $ImportLibraries,
        MailNotify $MailNotify

    ) {
        $this->import = $ImportLibraries;
        $this->send_mail = $MailNotify;
    }
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
    public function get_all_list($request)
    {
        $materials = $request->materials;
        $type = $request->type;
        $status = $request->status;
        $from = $request->from;
        $to   = $request->to;
        $warehouse_go   = $request->warehouse_go;
        $warehouse_to   = $request->warehouse_to;
        return ExportMaterials::where('IsDelete', 0)
            ->when($materials, function ($query, $materials) {
                return $query->where('Materials_ID', $materials);
            })
            ->when($type, function ($query, $type) {
                return $query->where('Type', $type);
            })
            ->when($status, function ($query, $status) {
                return $query->where('Status', $status);
            })
            ->when($warehouse_go, function ($query, $warehouse_go) {
                return $query->where('Go', $warehouse_go);
            })
            ->when($warehouse_to, function ($query, $warehouse_to) {
                return $query->where('To', $warehouse_to);
            })
            ->when($from, function ($query, $from) {
                return $query->where('Time_Created', '>=', Carbon::create($from)->startOfDay()->toDateTimeString());
            })
            ->when($to, function ($query, $to) {
                return $query->where('Time_Created', '<=', Carbon::create($to)->endOfDay()->toDateTimeString());
            })
            ->orderBy('ID', 'desc')
            ->paginate(10);
    }


    public function export_add($request)
    {
        // dd($request);
        $data = ExportMaterials::create([
            'Go'            => $request->Go,
            'To'            => $request->To,
            'Machine_ID'    => $request->Machine_ID,
            'Materials_ID'  => $request->Materials_ID,
            'Quantity'      => $request->Quantity,
            'Count'         => $request->Count,
            'Type'          => 0,
            'Status'        => 0,
            'User_Created'  => Auth::user()->id,
            'User_Updated'  => Auth::user()->id,
            'IsDelete'      => 0
        ]);
        $num = 1;
        $mater = $data->materials ? $data->materials->Symbols : '';
        $Go = $data->go ? $data->go->Symbols : '';
        $To = $data->to ? $data->to->Symbols : '';
        if ($data->go && $data->to) {
            if ($data->to->Accept == 0) {
                if ($data->to->Email) {
                    $this->send_mail->send_mail($data->to->Email, $num, $mater, $Go, $To, $data->Quantity, $data->Count);
                }
            } else {

                if ($data->go->Accept == 0) {
                    ExportMaterials::where('IsDelete', 0)
                        ->where('ID', $data->ID)
                        ->update([
                            'User_Updated'     => Auth::user()->id,
                            'Status'         => 1
                        ]);
                } else {
                    $this->accept((object)[
                        'ID' => $data->ID
                    ]);
                }
            }
        }
        else
        {
            if($data->go && $data->Machine_ID)
            {
                ExportMaterials::where('IsDelete', 0)
                ->where('ID', $data->ID)
                ->update([
                    'User_Updated'     => Auth::user()->id,
                    'Status'         => 1
                ]);
                $this->accept((object)[
                    'ID' => $data->ID
                ]);
            }
        }
        return true;
    }
    public function cancel($request)
    {
        // dd($request);
        return ExportMaterials::where('IsDelete', 0)
            ->where('ID', $request->ID)
            ->update([
                'User_Updated'     => Auth::user()->id,
                'Status'         => 4
            ]);
    }
    public function success($request)
    {
        $data = ExportMaterials::where('IsDelete', 0)
            ->where('ID', $request->ID)
            ->first();
        $a = count(collect($data->detail)->where('Status', 1));
        $b = count(collect($data->detail)->where('Transfer', 1));
        if ($a > $b && $data->Go != $data->To && !$data->Machine_ID) {
            $num1 = $a;
            $num2 = $b;
            $command1 =  $data->Type == 0 ? __('PM') : ($data->Type == 1 ? __('PDA') : __('HT'));
            $command2 = date_format(date_create($data->Time_Created), "YmdHis");
            $command = $command1 . '-' . $command2;
            $mater = $data->materials ? $data->materials->Symbols : '';
            $Go = $data->go ? $data->go->Symbols : '';
            $To = $data->to ? $data->to->Symbols : '';

            $this->send_mail->send_mail2($data->to->Email, $num1, $num2, $mater, $Go, $To, $command);
        }
        // dd($request);
        return ExportMaterials::where('IsDelete', 0)
            ->where('ID', $request->ID)
            ->update([
                'User_Updated'     => Auth::user()->id,
                'Status'         => 3
            ]);
    }
    public function accept($request)
    {
        // dd($run);
        $data =  ExportMaterials::where('IsDelete', 0)
            ->where('ID', $request->ID)
            ->first();
        if ($data->Status == 1) {
            $request->Materials_ID = $data->Materials_ID;
            $request->warehouse = [];
            if ($data->go) 
            {
                $ware = MasterWarehouseDetail::where('IsDelete',0)->where('Area',$data->Go)->get();
                    foreach ($ware as $key) {
                        array_push($request->warehouse, $key->ID);
                    }
            }
            $list = $this->import->get_list_with_materials($request);

            $arr = [];
            if ($data->Count) {
                $quan = $data->Count;
            } else {
                $quan = $data->Quantity;
            }

            // dd($quan);
            $dem = 0;
            foreach ($list as $value) {
                // dd($value);
                if ($quan > 0) {
                    // foreach($value as $value1)
                    // {
                    $arr1 = [
                        'Export_ID' => $data->ID,
                        'Pallet_ID' => $value->Pallet_ID,
                        'Box_ID'    => $value->Box_ID,
                        'Materials_ID' => $value->Materials_ID,
                        'Warehouse_Detail_ID' => $value->Warehouse_Detail_ID,
                        'Quantity'            => $value->Inventory,
                        'Status'              => 0,
                        'Type'                => 0,
                        'Supplier_ID'         => $value->Supplier_ID,
                        'STT'                 => $dem,
                        'User_Created'        => Auth::user()->id,
                        'User_Updated'        => Auth::user()->id,
                        'IsDelete'            => 0
                    ];
                    array_push($arr, $arr1);
                    // }
                }
            }
            foreach ($arr as $value) {
                ExportDetail::Create($value);
            }
        } else {
            $num = 2;
            $mater = $data->materials ? $data->materials->Symbols : '';
            $Go = $data->go ? $data->go->Symbols : '';
            $To = $data->to ? $data->to->Symbols : '';
            if ($data->go && $data->to) {
                if ($data->go->Accept == 0) {
                    if ($data->go->Email) {
                        $this->send_mail->send_mail($data->go->Email, $num, $mater, $Go, $To, $data->Quantity, $data->Count);
                    }
                } else {

                    ExportMaterials::where('IsDelete', 0)
                        ->where('ID', $request->ID)
                        ->update([
                            'User_Updated'     => Auth::user()->id,
                            'Status'         => 1
                        ]);

                    $this->accept((object)[
                        'ID' => $request->ID
                    ]);

                    return ExportMaterials::where('IsDelete', 0)
                        ->where('ID', $request->ID)
                        ->update([
                            'User_Updated'     => Auth::user()->id,
                            'Status'         => 2
                        ]);
                }
            }
        }
        return ExportMaterials::where('IsDelete', 0)
            ->where('ID', $request->ID)
            ->update([
                'User_Updated'     => Auth::user()->id,
                'Status'         => ($data->Status + 1)
            ]);
    }
    public function get_all_list_detail($request)
    {
        return ExportDetail::where('IsDelete', 0)
            ->where('Export_ID', $request->ID)
            ->with('materials', 'location')
            ->get();
    }
    public function get_filter_list_detail($request)
    {
        $Pallet_ID = $request->Pallet_ID;
        $Box_ID    = $request->Box_ID;
        $status    = $request->status;
        return ExportDetail::where('IsDelete', 0)
            ->where('Export_ID', $request->ID)
            ->when($Pallet_ID, function ($query, $Pallet_ID) {
                return $query->where('Pallet_ID', $Pallet_ID);
            })
            ->when($Box_ID, function ($query, $Box_ID) {
                return $query->where('Box_ID', $Box_ID);
            })
            ->when($status, function ($query, $status) {
                return $query->where('Status', $status);
            })
            ->get();
    }
    public function show($request)
    {
        return ExportMaterials::where('IsDelete', 0)
            ->where('ID', $request->ID)
            ->first();
    }
    public function export($request)
    {
        $pallet = $request->Pallet_ID;
        $location = MasterWarehouseDetail::where('IsDelete', 0)
            ->where('Symbols', $request->Location)
            ->first();
        if ($location) {
            $data =  ExportDetail::where('IsDelete', 0)
                ->where('Export_ID', $request->Command_ID)
                ->when($pallet, function ($query, $pallet) {
                    return $query->where('Pallet_ID', $pallet);
                })
                ->where('Box_ID', $request->Box_ID)
                ->where('Warehouse_Detail_ID', $location->ID)
                ->orderBy('ID', 'desc')
                ->first();

            $data1 =  ImportDetail::where('IsDelete', 0)
                ->when($pallet, function ($query, $pallet) {
                    return $query->where('Pallet_ID', $pallet);
                })
                ->where('Box_ID', $request->Box_ID)
                ->where('Warehouse_Detail_ID', $location->ID)
                ->orderBy('ID', 'desc')
                ->where('Inventory', '>', 0)
                ->first();
            // dd($data,$data1);
            if ($data && $data1 && $data->export) {
                if ($data1->Inventory > 0 || $data != 0) {
                    $command = ExportMaterials::where('IsDelete', 0)
                        ->where('ID', $request->Command_ID)
                        ->first();
                    if ($command) {
                        if ($command->Count) {
                            $quan = $command->Count;
                            $quan1 = count($command->detail->where('Status', 1));
                            if ($quan1 < $quan) {
                                ExportDetail::where('IsDelete', 0)
                                ->where('ID', $data->ID)
                                ->update([
                                        'Quantity'         => $request->Quantity,
                                        'Status'           => 1,
                                        'User_Updated'     => Auth::user()->id
                                ]);
                                ImportDetail::where('IsDelete', 0)
                                ->where('ID', $data1->ID)
                                ->update([
                                        'Inventory'        => $data1->Inventory - $request->Quantity,
                                        'User_Updated'     => Auth::user()->id
                                ]);
                                if($data->export->Machine_ID)
                                {
                                    $warehouse = MasterWarehouseDetail::where('IsDelete',0)->where('Machine_ID',$data->export->Machine_ID)->first();
                                    if($warehouse)
                                    {
                                        $arr = [
                                            'Materials_ID'     => $data1->Materials_ID,
                                            'Box_ID'           => $data1->Box_ID,
                                            'Supplier_ID'      => $data1->Supplier_ID,
                                            'Quantity'         => $request->Quantity,
                                            'Warehouse_Detail_ID' => $warehouse->ID,
                                            'Machine_ID'       => $warehouse->Machine_ID,
                                            'User_Created'     => Auth::user()->id,
                                            'User_Updated'     => Auth::user()->id,
                                            'IsDelete'         => 0
                                        ];
                                        StockMachine::create($arr);
                                    }
                                }
                                return __('Success');
                            } else {
                                return __('Quantity Requested Greater Than Allowed Quantity');
                            }
                        } else {
                            $quan1 = floatval(collect($command->detail->where('Status', 1))->sum('Quantity'));
                            // dd($quan1,$data->Quantity);
                            if ($quan1 < $data->export->Quantity) {
                                ExportDetail::where('IsDelete', 0)
                                    ->where('ID', $data->ID)
                                    ->update([
                                        'Quantity'         => $request->Quantity,
                                        'Status' => 1,
                                        'User_Updated'     => Auth::user()->id
                                    ]);

                                ImportDetail::where('IsDelete', 0)
                                    ->where('ID', $data1->ID)
                                    ->update([
                                        'Inventory'        => $data1->Inventory - $request->Quantity,
                                        'User_Updated'     => Auth::user()->id
                                    ]);
                                
                                    if($data->export->Machine_ID)
                                    {
                                        $warehouse = MasterWarehouseDetail::where('IsDelete',0)->where('Machine_ID',$data->export->Machine_ID)->first();
                                        if($warehouse)
                                        {
                                            $arr = [
                                                'Materials_ID'     => $data1->Materials_ID,
                                                'Box_ID'           => $data1->Box_ID,
                                                'Supplier_ID'      => $data1->Supplier_ID,
                                                'Quantity'         => $request->Quantity,
                                                'Warehouse_Detail_ID' => $warehouse->ID,
                                                'Machine_ID'       => $warehouse->Machine_ID,
                                                'User_Created'     => Auth::user()->id,
                                                'User_Updated'     => Auth::user()->id,
                                                'IsDelete'         => 0
                                            ];
                                            StockMachine::create($arr);
                                        }
                                    }    
                                return __('Success');
                            } 
                            else {
                                return __('Quantity Requested Greater Than Allowed Quantity');
                            }
                        }
                    } 
                    else {
                        return __('Command Not Define');
                    }
                } 
                else {
                    return __('Pallet or Box Not Define');
                }
            } 
            else {
                return __('Box') . ' ' . __('Was') . ' ' . __('Export');
            }
        } 
        else {
            return __('Location Not Define');
        }
    }
}

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
use App\Models\MasterData\MasterWarehouse;
use App\Models\MasterData\MasterProduct;
use App\Models\MasterData\MasterBOM;
use Auth;
use Carbon\Carbon;
use App\Models\WarehouseSystem\ProductReport;
use App\Models\WarehouseSystem\StockMachine;
class ProductReportLibaries
{

    private function read_file($request)
    {
        try
        {
            $file     = request()->file('fileImport');
            $name     = $file->getClientOriginalName();
            $arr      = explode('.', $name);
            $fileName = strtolower(end($arr));
            // dd($file, $name, $arr, $fileName);
            if ($fileName != 'xlsx' && $fileName != 'xls') 
            {
                return redirect()->back();
            } 
            else if($fileName == 'xls')
            {
                $reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else if($fileName == 'xlsx')
            { 
                $reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            try
            {
                $spreadsheet = $reader->load($file);
                $data        = $spreadsheet->getActiveSheet()->toArray();

                return $data;
            }  
            catch(\Exception $e)
            {
                return ['danger' => __('Select The Standard ".xlsx" Or ".xls" File')];
            }
        } 
        catch (\Exception $e)
        {
            return ['danger' => __('Error Something')];
        }
    }

    public function import_file_txt($request)
    {
            $file     = request()->file('fileImport');
            $name     = $file->getClientOriginalName();
            $arr      = explode('.', $name);
            $fileName = strtolower(end($arr));
            // dd($file);
            $text =  str_replace(' ', '',file_get_contents($file));
            $members = explode("\r\n",$text);
           
            // $myfile = fopen($file, "r") or die("Unable to open file!");
            // $readfile = fread($myfile,filesize($file)) ;
            // $members = explode('\r\n',  $readfile);
            $err = [];
            foreach($members as $key=> $value)
            {
                
                if($key > 0)
                {
                    // dd($value);
                    $data = explode("|",$value);
                    
                    // dd($data);
                    if(array_key_exists(7,$data) && array_key_exists(9,$data) && array_key_exists(11,$data))
                    {
                        // dd($data[7],$data[9],$data[11]);
                        $pro = MasterProduct::where('IsDelete',0)->where('Symbols',$data[9])
                        ->first();
                        if($pro)
                        {
                            ProductReport::create([
                                'Order_ID'=>$data[7],
                                'Product_ID'=>$pro->ID,
                                'Materials_Stock_ID'=>$pro->Materials_ID,
                                'Quantity'=>$data[11],
                                'Status'           => 0,
                                'User_Created'     => Auth::user()->id,
                                'User_Updated'     => Auth::user()->id,
                                'IsDelete'         => 0
                            ]);
                        }
                        else
						{
							$err1 = 'Dòng '.($key+1).' Có mã SP không tồn tại';
							array_push($err,$err1);
						}
                    }
                }
                
            }
            // fclose($myfile);
            return $err;
    }

    public function get_all_list()
    {
        return ProductReport::where('IsDelete',0)
        ->get();
    }
    public function get_all_list_paginate($request)
    {
        $Order = $request->Order;
        $Product = $request->Product;
        $Materials = $request->Materials;
        $from = $request->from;
        $to = $request->to;
        return ProductReport::where('IsDelete',0)
        ->when($Order, function($query, $Order)
		{
			return $query->where('Order_ID', $Order);
		})
        ->when($Product, function($query, $Product)
		{
			return $query->where('Product_ID', $Product);
		})
        ->when($Materials, function($query, $Materials)
		{
			return $query->where('Materials_Stock_ID', $Materials);
		})
        ->when($from, function($query, $from )
		{
			return $query->where('Time_Created','>=',  Carbon::create($from)->startOfDay()->toDateTimeString());
		})
        ->when($to, function($query, $to)
		{
			return $query->where('Time_Created','<=', Carbon::create($to)->endOfDay()->toDateTimeString());
		})
        ->paginate(10);
    }
    public function get_all_list_fil($request)
    {
        $Order = $request->Order;
        $Product = $request->Product;
        $Materials = $request->Materials;
        $from = $request->from;
        $to = $request->to;
        return ProductReport::where('IsDelete',0)
        ->when($Order, function($query, $Order)
		{
			return $query->where('Order_ID', $Order);
		})
        ->when($Product, function($query, $Product)
		{
			return $query->where('Product_ID', $Product);
		})
        ->when($Materials, function($query, $Materials)
		{
			return $query->where('Materials_Stock_ID', $Materials);
		})
        ->when($from, function($query, $from )
		{
			return $query->where('Time_Created','>=' , Carbon::create($from)->startOfDay()->toDateTimeString());
		})
        ->when($to, function($query, $to)
		{
			return $query->where('Time_Created', '<=' , Carbon::create($to)->endOfDay()->toDateTimeString());
		})
        ->get();
    }
    public function show($request)
    {
        return ProductReport::where('IsDelete',0)
        ->where('ID',$request->ID)
        ->with('product','materials') 
        ->first();
    }
    public function update($request)
    {
        // dd($request);
        $order = ProductReport::where('IsDelete',0)
        ->where('Order_ID',$request->Order)
        ->where('Machine_ID',$request->Machine_ID)
        ->first();
        $total = ($request->OK + $request->NG);
        // dd($total);
        $err = [];
        if($order)
        {

                ProductReport::where('IsDelete',0)
                ->where('ID',$order->ID)
                ->update([
                    'Quantity'=> $order->Quantity +  $total,
                    'OK'      => $order->OK + $request->OK,
                    'NG'      => $order->NG + $request->NG,
                    'User_Updated'     => Auth::user()->id,
                ]);
        }
        else
        {
            ProductReport::create([
                'Order_ID'  => $request->Order,
                'Machine_ID'=> $request->Machine_ID,
                'Product_ID'=>$request->Product_ID,
                'Quantity'  =>$total,
                'OK'        =>$request->OK,
                'NG'        =>$request->NG,
                'User_Created'     => Auth::user()->id,
                'User_Updated'     => Auth::user()->id,
                'IsDelete'         => 0
            ]);
        }
        $check_pro = StockMachine::where('IsDelete',0)
        ->where('Machine_ID',$request->Machine_ID)
        ->where('Product_ID',$request->Product_ID)
        ->where('Quantity','>',0)
        ->first();
        if($check_pro)
        {
            StockMachine::where('ID',$check_pro->ID)
            ->Update([
                'IsDelete'         => 1,
                'User_Updated'     => Auth::user()->id,
            ]);
            StockMachine::create([
                'Machine_ID'            =>$check_pro->Machine_ID,
                'Warehouse_Detail_ID'   =>$check_pro->Warehouse_Detail_ID,
                'Product_ID'            => $check_pro->Product_ID,
                'Quantity'              => $check_pro->Quantity + $total,
                'User_Created'     => Auth::user()->id,
                'User_Updated'     => Auth::user()->id,
                'IsDelete'         => 0
            ]);
        }
        else
        {
            StockMachine::create([
                'Machine_ID'=>$request->Machine_ID,
                'Warehouse_Detail_ID'=>$request->Machine,
                'Product_ID'        => $request->Product_ID,
                'Quantity'          => $total,
                'User_Created'     => Auth::user()->id,
                'User_Updated'     => Auth::user()->id,
                'IsDelete'         => 0
            ]);
        }
        $use = $request->QuantityUse;
        foreach($request->Box as $key => $value)
        {
            $check = StockMachine::where('IsDelete',0)->where('Machine_ID',$request->Machine_ID)->where('Box_ID',$value)->where('Quantity','>',0)->first();
            if($check)
            {
                StockMachine::where('ID',$check->ID)
                ->Update([
                    'IsDelete'     => 1,
                ]);
                StockMachine::create([
                    'Machine_ID'         => $check->Machine_ID,
                    'Warehouse_Detail_ID'=> $check->Warehouse_Detail_ID,
                    'Product_ID'         => $check->Product_ID,
                    'Materials_ID'       => $check->Materials_ID,
                    'Supplier_ID'        => $check->Supplier_ID,
                    'Box_ID'             => $check->Box_ID,
                    'Quantity'           => $check->Quantity - $use[$key],
                    'Use'                => $check->Use + $use[$key],
                    'NG'                 => $check->NG,
                    'User_Created'       => Auth::user()->id,
                    'User_Updated'       => Auth::user()->id,
                    'IsDelete'           => 0
                ]);
                
            }
        }
        
    }
    public function calculate($request)
    {
        $data = StockMachine::where('IsDelete',0)->WhereIn('Box_ID',$request->Box_ID)->get();
        $arr = [];
        $check = true;
        foreach($data->groupBy('Materials_ID') as $key => $value)
        {
            $pro = MasterBOM::where('IsDelete',0)->where('Product_BOM_ID',$request->Product_ID)->where('Materials_ID',$key)->first();
            if($pro)
            {
                
              
                foreach($value as $value1)
                {
                    $value1['QuantityProduction'] = ( $request->OK + $request->NG ) * $pro->Quantity_Materials / count($value);
                    if($value1['QuantityProduction'] > $value1->Quantity)
                    {
                        $check = false;
                    }
                    array_push($arr,$value1);
                }
            }  
        }

       return (object)[
        'check'=>$check,
        'data' => $arr
       ];
    }
}      

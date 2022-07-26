<?php

namespace App\Libraries\Report;

use Illuminate\Validation\Rule;
use App\Models\WarehouseSystem\CommandInventory;
use App\Models\WarehouseSystem\ImportDetail;
use App\Models\WarehouseSystem\InventoryMaterials;
use App\Models\WarehouseSystem\ExportDetail;
use Validator;
use ExportLibraries;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Models\MasterData\MasterMaterials;
use App\Models\MasterData\MasterSupplier;
use App\Models\MasterData\MasterWarehouse;
use Auth;
use Carbon\Carbon;

class NxtLibraries
{

    public function get_list_entry($request)
    {

        $ware = $request->Warehouse;
        $mater = $request->Materials;
        $supp = $request->Supplier;
        $from = $request->from ? Carbon::create($request->from)->startOfDay()->toDateTimeString() : Carbon::now()->startOfMonth()->toDateTimeString();
        $to = $request->to ? Carbon::create($request->to)->startOfDay()->toDateTimeString() : Carbon::now()->endOfMonth()->toDateTimeString();
        $list_ware = MasterWarehouse::where('IsDelete', 0)
            ->when($ware, function ($query, $ware) {
                return $query->where('ID', $ware);
            })
        ->get();
        $list_warehouse = [];
            foreach ($list_ware as $value) {
                foreach ($value->detail->where('Machine_ID',null) as $value1) {
                    array_push($list_warehouse, $value1->ID);
                }
            }
        $list_mater = MasterMaterials::where('IsDelete', 0)
            ->when($mater, function ($query, $mater) {
                return $query->where('ID', $mater);
            })
        ->get();
        $list_Supplier = MasterSupplier::where('IsDelete', 0)
            ->when($supp, function ($query, $supp) {
                return $query->where('ID', $supp);
            })
        ->get();
        $array = [];
        foreach ($list_mater as $value) 
        {
            //tồn đầu
            
            foreach($list_Supplier as $sl1)
            {
                $arr = [];
                $arr['supplier']= $sl1;
                $sl = $sl1->ID;
                $im = ImportDetail::where('IsDelete', 0)
                ->where('Materials_ID', $value->ID)
                ->whereIn('Warehouse_Detail_ID', $list_warehouse)
                ->where('Time_Import', '<', $from)
                ->where('Supplier_ID', $sl)
                ->where('Type', '<>', 3)
                ->get();  
                $ex = ExportDetail::where('IsDelete', 0)
                    ->where('Materials_ID', $value->ID)
                    ->where('Status', 1)
                    ->where('Type', '<>', 2)
                    ->where('Supplier_ID', $sl)
                    ->whereIn('Warehouse_Detail_ID', $list_warehouse)
                    ->where('Time_Updated', '<', $from)
                    ->get();
                $first_quan = collect($im)->sum('Quantity') - collect($ex)->sum('Quantity');
                $first_count = collect($im)->count() - collect($ex)->count();
                //nhập mới
                $im1 = ImportDetail::where('IsDelete', 0)
                    ->where('Materials_ID', $value->ID)
                    ->whereIn('Warehouse_Detail_ID', $list_warehouse)
                    ->whereIn('Type',[0,6])
                    ->where('Supplier_ID', $sl)
                    ->where('Time_Import', '>=', $from)
                    ->where('Time_Import', '<=', $to)
                    ->get();
                if(collect( $im1)->first())
                {
                    $value['supplier']=collect($im1)->first()->supplier;
                }
                $im1_count = collect($im1)->count();
                $im1_quan = collect($im1)->sum('Quantity');
                //nhập lại
                $im2 = ImportDetail::where('IsDelete', 0)
                    ->where('Materials_ID', $value->ID)
                    ->whereIn('Warehouse_Detail_ID', $list_warehouse)
                    ->where('Type', 1)
                    ->where('Supplier_ID', $sl)
                    ->where('Time_Import', '>=', $from)
                    ->where('Time_Import', '<=', $to)
                    ->get();
                $im2_count = collect($im2)->count();
                $im2_quan = collect($im2)->sum('Quantity');
                //nhập Kiểm Kê
                $im3 = ImportDetail::where('IsDelete', 0)
                    ->where('Materials_ID', $value->ID)
                    ->whereIn('Warehouse_Detail_ID', $list_warehouse)
                    ->where('Type', 2)
                    ->where('Supplier_ID', $sl)
                    ->where('Time_Import', '>=', $from)
                    ->where('Time_Import', '<=', $to)
                    ->get();
                $im3_count = collect($im3)->count();
                $im3_quan = collect($im3)->sum('Quantity');
                //Xuất sx
                $ex1 = ExportDetail::where('IsDelete', 0)
                    ->where('Materials_ID', $value->ID)
                    ->where('Status', 1)
                    ->where('Type', 0)
                    ->where('Supplier_ID', $sl)
                    ->whereIn('Warehouse_Detail_ID', $list_warehouse)
                    ->where('Time_Updated', '>=', $from)
                    ->where('Time_Updated', '<=', $to)
                    ->get();
                $ex1_count = collect($ex1)->count();
                $ex1_quan = collect($ex1)->sum('Quantity');
                //Xuất kk
                $ex2 = ExportDetail::where('IsDelete', 0)
                    ->where('Materials_ID', $value->ID)
                    ->where('Status', 1)
                    ->where('Type', 1)
                    ->where('Supplier_ID', $sl)
                    ->whereIn('Warehouse_Detail_ID', $list_warehouse)
                    ->where('Time_Updated', '>=', $from)
                    ->where('Time_Updated', '<=', $to)
                    ->get();
                $ex2_count = collect($ex2)->count();
                $ex2_quan = collect($ex2)->sum('Quantity');

                $arr['Symbols'] = $value->Symbols;
                $arr['Spec']    = $value->Spec;
                $arr['first1']    = $first_quan > 0 ?  $first_quan : 0;
                $arr['first']     = $first_count > 0 ?  $first_count : 0;
                $arr['im1']       = $im1_count > 0 ?  $im1_count : 0;
                $arr['imm1']      = $im1_quan > 0 ?  $im1_quan : 0;
                $arr['im2']       = $im2_count > 0 ?  $im2_count : 0;
                $arr['imm2']      = $im2_quan > 0 ?  $im2_quan : 0;
                $arr['im3']       = $im3_count > 0 ?  $im3_count : 0;
                $arr['imm3']      = $im3_quan > 0 ?  $im3_quan : 0;
                $arr['ex1']       = $ex1_count > 0 ?  $ex1_count : 0;
                $arr['exx1']      = $ex1_quan > 0 ?  $ex1_quan : 0;
                $arr['ex2']       = $ex2_count > 0 ?  $ex2_count : 0;
                $arr['exx2']      = $ex2_quan > 0 ?  $ex2_quan : 0;
                if ($first_quan > 0 || $im1_quan > 0 || $im2_quan > 0 || $im3_quan > 0 ||  $ex1_quan > 0 || $ex2_quan > 0) {
                    array_push($array, collect($arr));
                }
            }
            

           
        }
        return ($array);
    }
}
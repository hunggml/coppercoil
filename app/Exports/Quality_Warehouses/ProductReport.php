<?php

namespace App\Exports\Quality_Warehouses;


use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\IOFactory;
class ProductReport 
{
    public function export($data)
	{
		// $fileType = IOFactory::identify(public_path('template\excels\product_template.xlsx'));
        // //Load data
        // $loadFile = IOFactory::createReader($fileType);
        // dd($data);
        $name='Product_Report';
        $file = new Spreadsheet();
		$active_sheet = $file->getActiveSheet();
        $count = 1;
        $active_sheet->setCellValue('A' . $count, 'Mã lệnh');
        $active_sheet->setCellValue('B' . $count, 'Mã sản phẩm');
        $active_sheet->setCellValue('C' . $count, 'Nguyên vật liệu');
        $active_sheet->setCellValue('D' . $count, 'Đường kính dây đồng');
        $active_sheet->setCellValue('E' . $count, 'Định mức Nguyên vật liệu');
        $active_sheet->setCellValue('F' . $count, 'Tổng số lượng');
        $active_sheet->setCellValue('G' . $count,  'Tổng OK');
        $active_sheet->setCellValue('H' . $count, 'Tổng NG');
        $active_sheet->setCellValue('I' . $count, 'Khối lượng đồng OK');
        $active_sheet->setCellValue('J' . $count, 'Khối lượng đồng NG');
        $active_sheet->setCellValue('K' . $count, 'Tổng khối lượng');
        $count = 2;
		foreach($data as $value)
		{	 
            $active_sheet->setCellValue('A' . $count, $value->Order_ID);
            $active_sheet->setCellValue('B' . $count, $value->product ? $value->product->Symbols : '');
            $active_sheet->setCellValue('C' . $count, ($value->materials ? $value->materials->Symbols : '').'|'.($value->materials ? $value->materials->Spec : ''));
            $active_sheet->setCellValue('D' . $count, $value->materials ? $value->materials->Spec : '');
            $active_sheet->setCellValue('E' . $count, $value->product ? $value->product->Quantity : '');
            $active_sheet->setCellValue('F' . $count, floatval($value->Quantity));
            $active_sheet->setCellValue('G' . $count, floatval($value->OK));
            $active_sheet->setCellValue('H' . $count, floatval($value->NG));
            $active_sheet->setCellValue('I' . $count,  $value->product ? floatval($value->OK) * $value->product->Quantity : '');
            $active_sheet->setCellValue('J' . $count,  $value->product ? floatval($value->NG) * $value->product->Quantity : '');
            $active_sheet->setCellValue('K' . $count, $value->product ? (floatval($value->OK) * $value->product->Quantity) + (floatval($value->NG) * $value->product->Quantity) : '');
                $count = $count + 1;
		}
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file, 'Xlsx');
		$file_name = $name . '.' . strtolower('Xlsx');
		$writer->save($file_name);
		header('Content-Type: application/x-www-form-urlencoded');
		header('Content-Transfer-Encoding: Binary');
		header("Content-disposition: attachment; filename=\"".$file_name."\"");
		readfile($file_name);
		unlink($file_name);
		exit;
	}
    
}

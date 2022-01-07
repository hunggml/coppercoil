<?php

namespace App\Exports\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;  
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\MasterData\MasterMaterials;
use App\Libraries\MasterData\MasterMaterialsLibraries;

/**
 * 
 */
class MasterMaterialsExport
{
     public function export($data)
	{
		// $fileType = IOFactory::identify(public_path('template\excels\product_template.xlsx'));
        // //Load data
        // $loadFile = IOFactory::createReader($fileType);
        // dd($data);
        $name='Materials';
        $file = new Spreadsheet();
		$active_sheet = $file->getActiveSheet();
        $count = 1; 
        $active_sheet->setCellValue('A' . $count, 'Tên nguyên vật liệu');
        $active_sheet->setCellValue('B' . $count, 'Mã nguyên vật liệu');
        $active_sheet->setCellValue('C' . $count, 'Nhà cung cấp');
        $active_sheet->setCellValue('D' . $count, 'Kiểu dây');
        $active_sheet->setCellValue('E' . $count, 'Đường kính dây');
        $active_sheet->setCellValue('F' . $count, 'Đơn vị tính');
        $active_sheet->setCellValue('G' . $count, 'Đơn đóng gói');
        $active_sheet->setCellValue('H' . $count, 'Tỷ lệ chênh lệch khối lượng khi nhập');
        $active_sheet->setCellValue('I' . $count, 'Định mức tối thiểu tồn kho');

      	
        $count = 2;
		foreach($data as $value)
		{

          $range = 'E'.$count.':E'.$count;
          $active_sheet
              ->getStyle($range)
              ->getNumberFormat()
              ->setFormatCode( \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT );

            $active_sheet->setCellValue('A' . $count, $value->Name);
            $active_sheet->setCellValue('B' . $count, $value->Symbols);
            $active_sheet->setCellValue('C' . $count, $value->Supplier_ID ? $value->supplier->Symbols : '');
            $active_sheet->setCellValue('D' . $count, $value->Wire_Type);
            $active_sheet->setCellValue('E' . $count, $value->Spec);
            $active_sheet->setCellValue('F' . $count, $value->Unit_ID ? $value->unit->Symbols : '');
            $active_sheet->setCellValue('G' . $count, 'box');
            $active_sheet->setCellValue('H' . $count, $value->Difference);
            $active_sheet->setCellValue('I' . $count, '');
            
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
<?php

namespace App\Exports\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;  
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\MasterData\MasterProduct;
use App\Libraries\MasterData\MasterProductLibraries;

/** 
 * 
 */
class MasterProductExport
{
	public function export($data)
	{
		// $fileType = IOFactory::identify(public_path('template\excels\product_template.xlsx'));
        // //Load data
        // $loadFile = IOFactory::createReader($fileType);
        // dd($data);
		$name='Product';
		$file = new Spreadsheet();
		$active_sheet = $file->getActiveSheet();
		$count = 1; 
		$active_sheet->setCellValue('A' . $count, 'Tên sản phẩm');
		$active_sheet->setCellValue('B' . $count, 'Mã sản phẩm');
		$active_sheet->setCellValue('C' . $count, 'Nguyên vật liệu');
		$active_sheet->setCellValue('D' . $count, 'Khối lượng(kg)');
		$active_sheet->setCellValue('E' . $count, 'Đơn vị tính');

		
		$count = 2;
		foreach($data as $value)
		{	 
			$range = 'D'.$count.':D'.$count;
          	$active_sheet
              ->getStyle($range)
              ->getNumberFormat()
              ->setFormatCode( \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT );

			$active_sheet->setCellValue('A' . $count, $value->Name);
			$active_sheet->setCellValue('B' . $count, $value->Symbols);
			$active_sheet->setCellValue('C' . $count, $value->Materials_ID ? $value->materials->Symbols : '');
			$active_sheet->setCellValue('D' . $count, $value->Quantity);
			$active_sheet->setCellValue('E' . $count, $value->Unit_ID ? $value->unit->Symbols : '');
			
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
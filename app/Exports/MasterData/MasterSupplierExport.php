<?php

namespace App\Exports\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;  
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\MasterData\MasterSupplier;
use App\Libraries\MasterData\MasterSupplierLibraries;

/**
 * 
 */
class MasterSupplierExport
{
     public function export($data)
	{
		// $fileType = IOFactory::identify(public_path('template\excels\product_template.xlsx'));
        // //Load data
        // $loadFile = IOFactory::createReader($fileType);
        // dd($data);
        $name='Supplier';
        $file = new Spreadsheet();
		$active_sheet = $file->getActiveSheet();
        $count = 1; 
        $active_sheet->setCellValue('A' . $count, 'Tên nhà cung cấp');
        $active_sheet->setCellValue('B' . $count, 'Mã nhà cung cấp');
        $active_sheet->setCellValue('C' . $count, 'Địa chỉ');
        $active_sheet->setCellValue('D' . $count, 'Người liên hệ');
        $active_sheet->setCellValue('E' . $count, 'Số điện thoại');
        $active_sheet->setCellValue('F' . $count, 'Mã số thuế');
      	
        $count = 2;
		foreach($data as $value)
		{	 
            $active_sheet->setCellValue('A' . $count, $value->Name);
            $active_sheet->setCellValue('B' . $count, $value->Symbols);
            $active_sheet->setCellValue('C' . $count, $value->Address);
            $active_sheet->setCellValue('D' . $count, $value->Contact);
            $active_sheet->setCellValue('E' . $count, $value->Phone);
            $active_sheet->setCellValue('F' . $count, $value->Tax_Code);
            
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
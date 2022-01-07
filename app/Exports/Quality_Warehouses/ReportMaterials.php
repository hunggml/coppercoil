<?php

namespace App\Exports\Quality_Warehouses;


use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\IOFactory;
class ReportMaterials 
{
    public function export($data,$request)
	{
        $name='Report_Materials';
        $file = new Spreadsheet();
		$active_sheet = $file->getActiveSheet();
        $count = 1;
        $active_sheet->setCellValue('A' . $count, 'STT');
        $active_sheet->setCellValue('B' . $count, 'Nguyên vật liệu');
        $active_sheet->setCellValue('C' . $count, 'Đường kính dây đồng');
        $active_sheet->setCellValue('D' . $count, 'Tồn Đầu');
        $active_sheet->setCellValue('E' . $count, 'Nhập Mới');
        $active_sheet->setCellValue('F' . $count, 'Nhập Lại');
        $active_sheet->setCellValue('G' . $count, 'Nhập Kiểm Kê');
        $active_sheet->setCellValue('H' . $count, 'Xuất');
        $active_sheet->setCellValue('I' . $count, 'Xuất Kiểm Kê');
        $active_sheet->setCellValue('J' . $count, 'Tồn Cuối');
        $count = 2;
        $dem = 0;
		foreach($data as $value)
		{	 
            if(!$request->Type)
            {
                $dem++;
                $active_sheet->setCellValue('A' . $count, $dem);
                $active_sheet->setCellValue('B' . $count, $value->Symbols);
                $active_sheet->setCellValue('C' . $count, $value->Spec);
                $active_sheet->setCellValue('D' . $count, $value->first1);
                $active_sheet->setCellValue('E' . $count, $value->imm1);
                $active_sheet->setCellValue('F' . $count, $value->imm2);
                $active_sheet->setCellValue('G' . $count, $value->imm3);
                $active_sheet->setCellValue('H' . $count, $value->exx1);
                $active_sheet->setCellValue('I' . $count, $value->exx2);
                $active_sheet->setCellValue('J' . $count, ($value->first1 + $value->imm1 + $value->imm2 + $value->imm3 - $value->exx1 -$value->exx2 ) < 0.000000001 ? 0 : ($value->first1 + $value->imm1 + $value->imm2 + $value->imm3 - $value->exx1 -$value->exx2 ) );
                $count = $count + 1;
            }
            else
            {
                $dem++;
                $active_sheet->setCellValue('A' . $count, $dem);
                $active_sheet->setCellValue('B' . $count, $value->Symbols);
                $active_sheet->setCellValue('C' . $count, $value->Spec);
                $active_sheet->setCellValue('D' . $count, $value->first);
                $active_sheet->setCellValue('E' . $count, $value->im1);
                $active_sheet->setCellValue('F' . $count, $value->im2);
                $active_sheet->setCellValue('G' . $count, $value->im3);
                $active_sheet->setCellValue('H' . $count, $value->ex1);
                $active_sheet->setCellValue('I' . $count, $value->ex2);
                $active_sheet->setCellValue('J' . $count, ($value->first + $value->im1 + $value->im2 + $value->im3 - $value->ex1 -$value->ex2 ) < 0.000000001 ? 0 : ($value->first + $value->im1 + $value->im2 + $value->im3 - $value->ex1 -$value->ex2 ) );
                $count = $count + 1;
            }
            
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

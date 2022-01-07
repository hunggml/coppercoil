<?php
namespace App\Exports\Quality_Warehouses;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
/**
 * 
 */
class InventoriesMaterials
{
	public function export_file_excel($command, $data)
	{

		// dd($command, $data);
		$spreadsheet = new Spreadsheet();
		$arrayData = [
			[
				__('Inventories Materials List'), 
				NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL
			],
			[NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL],
			[NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL],
			[
				__('No'),
				__('Position'), 
				__('System'),
				__('System'),
				__('System'),
				__('System'),
				__('Actual'), 
				__('Actual'), 
				__('Actual'), 
				__('Actual'), 
				__('Total'), 
			],
			[
				NULL,
				NULL, 
				__('Label'),
				__('Materials'),
				__('Model'),
				__('Quantity'),
				__('Label'),
				__('Materials'),
				__('Model'),
				__('Quantity'), 
				NULL, 
			],
		];
		$row = 7;
		$stt = 1;
		$dataAll = array();
		foreach($data as $dat)
		{
	        foreach($dat as $key => $value)
	        {
	        	$arr = [
	        		$stt,
	        		$value->warehouse ? $value->warehouse->Symbols : '' ,
                    $value->import ? 
                    (
                        $value->import->label ? str_pad($value->import->label->ID, 10, "0", STR_PAD_LEFT) : ''
                    )
                    : '' 
                    ,
                    $value->import ? 
                    (
                        $value->import->label ? 
                        (
                            $value->import->label->materials ? 
                            $value->import->label->materials->Symbols
                            : ''
                        )
                        : ''
                    )
                    : '' 
                    ,
                    $value->import ? 
                    (
                        $value->import->label ? 
                        (
                            $value->import->label->materials ? 
                            $value->import->label->materials->Model
                            : ''
                        )
                        : ''
                    )
                    : '' 
                    ,
                     
                    floatval($value->Quantity_System)
                    ,
                    $value->label ? 
                    (
                        str_pad($value->label->ID, 10, "0", STR_PAD_LEFT)
                    )
                    : '' 
	                ,
                    $value->label ? 
                    (
                        $value->label->materials ? $value->label->materials->Symbols : ''
                    )
                    : '' 
                    ,
                    $value->label ? 
                    (
                        $value->label->materials ? $value->label->materials->Model : ''
                    )
                    : '' 
                    ,
                    $value->Quantity != 0 ? floatval($value->Quantity) : '' 
                    ,
                    floatval( $value->Quantity - $value->Quantity_System) 
	        	];

	        	$stt++;
	        	$row++;
	        	array_push($arrayData, $arr);
	        }
	    }

	    // dd($arrayData);

		$sheet = $spreadsheet->getActiveSheet();

		$cell = 'A';
		$to   = 'K';
		// dd($arrayData);
	    $sheet->fromArray(
	        $arrayData,  // The data to set
	        NULL,        // Array values with this value will not be set
	        $cell.'1'         // Top left coordinate of the worksheet range where
	                     //    we want to set these values (default is A1)
	    );



	    $sheet->mergeCells($cell."1:".$to."1");
	    $sheet->mergeCells("A4:A5");
	    $sheet->mergeCells("B4:B5");
	    $sheet->mergeCells("C4:F4");
	    $sheet->mergeCells("G4:J4");
	    $sheet->mergeCells("K4:K5");

	    $styleArray = array(
            'borders' => [
                'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color'       => ['argb' => '00000000'],
					'textAlign'   => 'center'
					// 'textAlign'   => 'left'
                ],
            ],
            'alignment' => [
			    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],
        );

        $newStyle = array(
        	'borders' => [
                'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color'       => ['argb' => '00000000'],
					'textAlign'   => 'center'
					// 'textAlign'   => 'left'
                ],
            ],
        	'alignment' => [
			    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],
			'font' => [
	            'bold' => true,
	        ],
        );

        $newStyle1 = array(
        	'alignment' => [
			    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],
        );

	    $sheet->getStyle($cell.'5:'.$to.($row))->applyFromArray($styleArray);
	    $sheet->getStyle($cell.'1')->getFont()->setBold(true)->setSize(23);
	    $sheet->getStyle("A4:A5")->applyFromArray($newStyle);
	    $sheet->getStyle("B4:B5")->applyFromArray($newStyle);
	    $sheet->getStyle("C4:F4")->applyFromArray($newStyle);
	    $sheet->getStyle("G4:J4")->applyFromArray($newStyle);
	    $sheet->getStyle("K4:K5")->applyFromArray($newStyle);
	    $sheet->getStyle("A5:K5")->applyFromArray($newStyle);

	    $sheet->getStyle("A1")->getAlignment()
	    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

	    // $sheet->getStyle("A7:E8")->getAlignment()
	    // ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

	    // $sheet->getStyle("D9:D".($row+2))->getAlignment()
	    // ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

	    // $sheet->getStyle("A9:A".($row+2))->getAlignment()
	    // ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

	    // $sheet->getRowDimension('1')->setRowHeight(35);
	    // $sheet->getStyle($cell.'8:'.$to.'8')->getFont()->setBold(true);
	    
	    for ($i=0; $i < 100 ; $i++) 
	    { 
	    	$sheet->getColumnDimension($cell)->setAutoSize(true);

	    	if ($cell == $to)
	    	{
	    		break;
	    	}
	    	$cell++;
	    }

		$writer    = new Xlsx($spreadsheet);
		$time      = Carbon::now()->toDateString();
		$file_name = 'inventories-list-'.$time.'.xlsx';
		$writer->save($file_name);
		header('Content-Type: application/x-www-form-urlencoded');
        header('Content-Transfer-Encoding: Binary');
        header("Content-disposition: attachment; filename=\"".$file_name."\"");
        readfile($file_name);
        unlink($file_name);
        exit;
	}
}
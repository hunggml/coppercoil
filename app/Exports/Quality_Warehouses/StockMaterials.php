<?php
namespace App\Exports\Quality_Warehouses;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
/**
 * 
 */
class StockMaterials
{
	public function export_file_excel($command, $data)
	{

		// dd($command, );
		$spreadsheet = new Spreadsheet();
		$arrayData = [
			[
				__('List').' '.__('Import').' '.__('Materials'),
				NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL
			],
			[NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL],
			[NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL],
			[
				__('No'),
				__('Part Number'), 
				__('Part No'),
				__('Supplier'),
				__('Lot'),
				__('Position'),
				// __('Floor'),
				__('Total'), 
			],
		];

		$row = 7;
		$stt = 1;
		$dataAll = array();

		foreach($data->groupBy('Materials_ID') as $dat)
		{
			// dd($dat->first());
			$value = $dat->first();
	        // foreach($dat as $key => $value)
	        // {
	        	$arr = [
	        		$stt,
	        		$value->label ? ($value->label->materials ? $value->label->materials->Symbols : '') : '' ,
	        		$value->label ? ($value->label->materials ? $value->label->materials->Model : '') : '' ,
	        		$value->label ? ($value->label->materials ? ($value->label->materials->supplier ? $value->label->materials->supplier->Symbols : '') : '') : '' ,
	        		$value->label ? $value->label->Lot_Number : '' ,
	        		$value->warehouse ? $value->warehouse->Symbols : '' ,
	        		// $value->warehouse ? $value->warehouse->Floor : '' ,
	        		floatval($dat->sum('Quantity')),
	        	];

	        	$stt++;
	        	$row++;
	        	array_push($arrayData, $arr);
	        // }
	    }

	    // dd($arrayData);

		$sheet = $spreadsheet->getActiveSheet();

		$cell = 'A';
		$to   = 'G';
		// dd($arrayData);
	    $sheet->fromArray(
	        $arrayData,  // The data to set
	        NULL,        // Array values with this value will not be set
	        $cell.'1'         // Top left coordinate of the worksheet range where
	                     //    we want to set these values (default is A1)
	    );



	    $sheet->mergeCells($cell."1:".$to."1");
	    // $sheet->mergeCells("A4:A5");
	    // $sheet->mergeCells("B4:B5");
	    // $sheet->mergeCells("C4:F4");
	    // $sheet->mergeCells("G4:J4");
	    // $sheet->mergeCells("K4:K5");

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
	    $sheet->getStyle("A4:G4")->applyFromArray($newStyle);
	    // $sheet->getStyle("B4:B5")->applyFromArray($newStyle);
	    // $sheet->getStyle("C4:F4")->applyFromArray($newStyle);
	    // $sheet->getStyle("G4:J4")->applyFromArray($newStyle);
	    // $sheet->getStyle("K4:K5")->applyFromArray($newStyle);
	    // $sheet->getStyle("A5:K5")->applyFromArray($newStyle);

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
		$file_name = 'import-list-'.$time.'.xlsx';
		$writer->save($file_name);
		header('Content-Type: application/x-www-form-urlencoded');
        header('Content-Transfer-Encoding: Binary');
        header("Content-disposition: attachment; filename=\"".$file_name."\"");
        readfile($file_name);
        unlink($file_name);
        exit;
	}
}
<?php

namespace App\Exports\Kitting;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

/**
 * DeliveryNoteDetail
 */
class DeliveryNoteDetail
{
	// public function __construct
	// {
		
	// }

	public function export($request)
	{
		$spreadsheet = new Spreadsheet();
		$arrayData = [
			[
				__('Export Materials List'), 
				NULL, NULL, NULL, NULL
			],
			[NULL, NULL, NULL, NULL, NULL],
			[NULL, NULL, NULL, NULL, NULL],
			[NULL, NULL, NULL, NULL, NULL],
			[NULL, NULL, NULL, NULL, NULL],
			[NULL, NULL, NULL, NULL, NULL],
			[NULL, NULL, NULL, NULL, NULL],
			[
				__('No'),
				// __('Name').' '.__('Production'), 
				__('Part Number'), 
				__('Part Name'), 
				// __('Face'), __('Machine'), 
				// __('Production Shift'), 
				__('Quantity'), 
				// __('Label'),
				__('Position'), 
				// __('Time Created'), 
				// __('User Created')
			],
		];

		$row = 7;
		$stt = 1;
		$machine          = array();
		$nameMain         = array();
		$production_shift = array();
		$face             = array();
		$dataAll = collect([]);

		foreach ($request as $key => $detail) 
		{
			if ($detail->detail_kitting) 
			{
				foreach ($detail->detail_kitting as $key1 => $kitting) 
				{
					$dataAll->push($kitting);
					$name = '';
					if ($kitting->product) 
					{
						$text = __('Night Shift');

						if ($kitting->Production_Shift == '1') 
						{
							$text = __('Day Shift');
						}

						if ($kitting->machine) 
						{
							array_push($machine, $kitting->machine->Symbols);
						}
						
						array_push($nameMain, $kitting->product->Symbols);
						array_push($production_shift, $text);
						array_push($face, $kitting->Face);
					}
				}
			}
		}
		// dd($dataAll->groupBy('Materials_ID') );
		foreach ($dataAll->groupBy('Materials_ID') as $key => $kittingList) 
		{
			foreach ($kittingList as $key1 => $kitting) 
			{
				$name = '';
				// var_dump($key1, $name);
				// dd($kittingList, $dataAll->groupBy('Materials_ID'));

				if($kitting->materials)
				{
                    foreach($kitting->materials->group as $groups)
                    {
                        foreach($groups->positions as $position)
                        {
                        	if ($name == '') 
                        	{
                        		$name = $position->Name;
                        	} else
                        	{
                            	$name = $name.','.$position->Name;
                        	}
                        }
                    }
                }

				if ($key1 == 0) 
				{
					$arr = [
						$stt,
						// $kitting->product ? $kitting->product->Symbols : NULL,
						$kitting->bom ? $kitting->bom->Part_Name : NULL,
						$kitting->materials ? $kitting->materials->Symbols : NULL,
						// $kitting->Face,
						// $kitting->machine ? $kitting->machine->Symbols : NULL,
						// $kitting->Production_Shift == 1 ? __('Day Shift') : __('Night Shift'),
						floatval($kittingList->sum('Quantity')),
						// str_pad($kitting->Label_ID, 9, '0',STR_PAD_LEFT),
		                $name,
						// $kitting->Time_Created,
						// $kitting->user_created ? $kitting->user_created->username : NULL 
					];
					// dd($arr, $value);
					$row++;
					$stt++;
					array_push($arrayData, $arr);
				}
			}
		}
		// dd($arrayData);

		$arrayData[2] = [__('Name').' '.__('Production')." : ", NULL, implode(',', array_unique($nameMain)), __('Production Shift') ." : ", implode(',', array_unique($production_shift))];

		$arrayData[3] = [__('Line').' '.__('Production') ." : ", NULL, implode(',', array_unique($machine)), __('Face').'  '.__('Production') ." : ", implode(',', array_unique($face))];

		$arrayData[4] = [__('Time Created') ." : ", NULL, ($request->first()->Time_Created->toDateString()), __('User Created') ." : ", ($request->first()->user_created ? $request->first()->user_created->username : '')];

		$arrayData[5] = [__('Kitting List Number')." : ", NULL,($request->first()->Kitting_Name), NULL, NULL];


		$sheet = $spreadsheet->getActiveSheet();

		$cell = 'A';
		$to   = 'E';
		// dd($arrayData);
	    $sheet->fromArray(
	        $arrayData,  // The data to set
	        NULL,        // Array values with this value will not be set
	        $cell.'1'         // Top left coordinate of the worksheet range where
	                     //    we want to set these values (default is A1)
	    );



	    $sheet->mergeCells($cell."1:".$to."1");
	    $sheet->mergeCells("A3:B3");
	    $sheet->mergeCells("A4:B4");
	    $sheet->mergeCells("A5:B5");
	    $sheet->mergeCells("A6:B6");

	    $styleArray = array(
            'borders' => [
                'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color'       => ['argb' => '00000000'],
					// 'textAlign'   => 'center'
					'textAlign'   => 'left'
                ],
            ],
        );

        $newStyle = array(
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

	    $sheet->getStyle($cell.'8:'.$to.($row+2))->applyFromArray($styleArray);
	    $sheet->getStyle($cell.'1')->getFont()->setBold(true)->setSize(23);
	    $sheet->getStyle('A3:A6')->applyFromArray($newStyle);
	    $sheet->getStyle('C3:C6')->applyFromArray($newStyle1);
	    $sheet->getStyle('D3:D6')->applyFromArray($newStyle);
	    $sheet->getStyle('E3:E6')->applyFromArray($newStyle1);


	    $sheet->getStyle($cell.'1:'.$to.($row+2))->getAlignment()
	    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

	    $sheet->getStyle("A1")->getAlignment()
	    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

	    $sheet->getStyle("A7:E8")->getAlignment()
	    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

	    $sheet->getStyle("D9:D".($row+2))->getAlignment()
	    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

	    $sheet->getStyle("A9:A".($row+2))->getAlignment()
	    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

	    $sheet->getRowDimension('1')->setRowHeight(35);
	    $sheet->getStyle($cell.'8:'.$to.'8')->getFont()->setBold(true);
	    
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
		$file_name = 'kitting-list-'.$time.'.xlsx';
		$writer->save($file_name);
		header('Content-Type: application/x-www-form-urlencoded');
        header('Content-Transfer-Encoding: Binary');
        header("Content-disposition: attachment; filename=\"".$file_name."\"");
        readfile($file_name);
        unlink($file_name);
        exit;
	}

}
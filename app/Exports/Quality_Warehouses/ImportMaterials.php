<?php
namespace App\Exports\Quality_Warehouses;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use App\Libraries\WarehousesManagement\ImportMaterialsLibraries;
use App\Libraries\PrintLabel\LabelLibraries;
use App\Libraries\MasterData\MasterWarehouseDetailLibraries;
use App\Libraries\MasterData\MasterMaterialsLibraries;
/**
 * 
 */
class ImportMaterials
{
	private $import_materials;
	private $label;
	private $warehouse;
	private $materials;

	public function __construct(
		ImportMaterialsLibraries $importMaterialsLibraries
		,LabelLibraries $labelLibraries
		,MasterWarehouseDetailLibraries $masterWarehouseDetailLibraries
		,MasterMaterialsLibraries $masterMaterialsLibraries
	){
		$this->import_materials = $importMaterialsLibraries;
		$this->label            = $labelLibraries;
		$this->warehouse 		= $masterWarehouseDetailLibraries;
		$this->materials 		= $masterMaterialsLibraries;
	}

	public function export_file_excel($request)
	{
		// dd($request);
    	$data = $this->get_data_use_paginate($request);
        // dd($data->data->all());
    	// $dataAll = collect(array());
    	// $dataAll = $data->data->all();

    	$numberPage = round($data->count/1000) + 1;

    	$command = true;
    	if ($request->Inventory) 
    	{
    		$command = false;	
    	}

		// dd($command, $data->sum('Inventory'));
		if ($command) 
		{
			$nameFile = __('List').' '.__('Import').' '.__('Materials');
		} else
		{
			$nameFile = __('List').' '.__('Stock').' '.__('Materials');
		}
		$spreadsheet = new Spreadsheet();
		$arrayData = [
			[
				$nameFile,
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
				// __('Shift'),
				// __('Shift'),
				// __('Type'),
			],
		];

		$row = 7;
		$stt = 1;
		$dataAll = array();
		// $dataEach = $data->groupBy('Materials_ID');
		for ($i=0; $i <= $numberPage ; $i++) 
    	{ 
            // var_dump($i);
    		$request->request->add([
                'page'  => $i,
                'count' => $numberPage
            ]);

    		$data = $this->get_data_use_paginate($request);
            // dd($data->data->all());
    		// $dataAll = array_merge($dataAll, $data->data->all());
    		$dataEach = $data->data;
			//->whereNotBetween('Time_Created', ['2020-02-19 00:00:00', '2020-02-19 18:00:00'])->whereNotBetween('Time_Created', ['2020-02-20 00:00:00', '2020-02-20 18:00:00']);
			// dd($data->where('Type', 1)->first());
			foreach($dataEach as $dat)
			{
				// $value = $dat->first();
				$value = $dat;
				// dd($value);
				// dd($dat);

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
		        		// $command ? floatval($dat->sum('Quantity')) : floatval($dat->sum('Inventory')),
		        		floatval($dat->Quantity),
		        		// $dat->Time_Created->toDateString(),
		        		// $dat->Time_Created->toDateTimeString(),
		        		// $dat->Type,
		        	];

		        	$stt++;
		        	$row++;
		        	array_push($arrayData, $arr);
		        // }
		    }
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
		if ($command) 
		{
			$file_name = 'import-list-'.$time.'.xlsx';
		} else
		{
			$file_name = 'stock-list-'.$time.'.xlsx';
		}

		$writer->save($file_name);
		header('Content-Type: application/x-www-form-urlencoded');
        header('Content-Transfer-Encoding: Binary');
        header("Content-disposition: attachment; filename=\"".$file_name."\"");
        readfile($file_name);
        unlink($file_name);
        exit;
	}

	private function get_data_use_paginate($request)
    {
    	$from 		= $request->From;
    	$to 		= $request->To;
    	$materials 	= $request->Materials;
    	$no 		= $request->No;
    	$floor 		= $request->Floor;
        $type       = $request->Type;
    	$label 		= array();
    	$inven 		= $request->Inventory;
        $count      = $request->count;

    	$materialss 	= $this->materials->get_all_list_materials();

    	$materialsFind 	= $materialss->when($materials, function($q, $materials)
    	{
    		return $q->where('Symbols', $materials);
    	})->when($no, function($q, $no)
    	{
    		return $q->where('Model', $no);
    	});

    	$warehouse = $this->warehouse->filter_floor((object)[
    		'Floor'	=> $floor
    	]);

    	$mats = $materialsFind->pluck('ID')->toArray();

    	if (!$request->Materials && !$request->No) 
    	{
    		$mats = [0];
    	}

    	$data 		= $this->import_materials->get_list_import_paginate_random((object)[
    		'from'	=> $from,
    		'to'	=> $to,
    		'inven'	=> $inven,
            'type'  => $type,
    		'label' => array_unique($mats),
    		'warehouse' => array_unique($warehouse->pluck('ID')->toArray()),
    		'page'	=> $request->page ? $request->page : 0
    	]);

        if (!$count) {
            $count       = $this->import_materials->get_number_page((object)[
                'from'  => $from,
                'to'    => $to,
                'inven' => $inven,
                'type'  => $type,
                'label' => array_unique($mats),
                'warehouse' => array_unique($warehouse->pluck('ID')->toArray()),
                'page'  => 1
            ]);
        }

    	return (object)[
            'data'  => $data,
            'count' => $count
        ];
    }
}
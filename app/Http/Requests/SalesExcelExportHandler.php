<?php

namespace App\Http\Requests;

use Maatwebsite\Excel\Files\ExportHandler;

class SalesExcelExportHandler implements ExportHandler {

    public function handle($file)
    {
        // change all data that need to be formatted
        foreach($file->data as &$datum) {
            foreach ($datum as $key => &$value) {
                if ($key == 'agent_id') {
                    $value = $datum['agent']['name'];
                    // change key
                    $datum['agent'] = $value;
                    unset($datum[$key]);
                } elseif ($key == 'product_id') {
                    $value = $datum['product']['product_name'];
                    // change key
                    $datum['product'] = $value;
                    unset($datum[$key]);
                } elseif ($key == 'customer_id') {
                    $value = $datum['customer']['name'];
                    // change key
                    $datum['customer'] = $value;
                    unset($datum[$key]);
                } elseif($key == 'MGI_start_date') {
					$value = date_format(date_create($value),'m/d/Y');
					//$value = \DateTime::createFromFormat('Y-m-d', $value)->format('m-d-Y'); // excel format
                }
				else if($key == 'branch_office_id')
				{
					$value = \App\BranchOffice::getBranchOfficeFromId($datum['branch_office_id'])->branch_name;
				}
            }
        }
        // work on the exportAgentsExcelExport
        return $file->sheet('sales', function($sheet) use($file)
        {
            $sheet->cells('1', function($cells) {
                $cells->setFontSize(12);
                $cells->setFontWeight('bold');
            });
            $sheet->fromArray($file->data, null, 'A1', true, true);
        })->export('xlsx');
    }

}

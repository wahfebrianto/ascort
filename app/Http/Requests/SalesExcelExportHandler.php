<?php

namespace App\Http\Requests;

use Maatwebsite\Excel\Files\ExportHandler;

class SalesExcelExportHandler implements ExportHandler {

    public function handle($file)
    {
		$ctr= 1;
		$accumulator = 0;
        // change all data that need to be formatted
        foreach($file->data as &$datum) {
            /*foreach ($datum as $key => &$value) {
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
            }*/
			$customer = \App\Customer::getCustomerFromId($datum['customer_id']);
			$agent = \App\Agent::getAgentFromId($datum['agent_id']);
			$parent = \App\Agent::getAgentFromId($agent->parent_id);
			$newDatum['No'] = $ctr;
			$ctr++;
			$newDatum['Nama Investor'] = $customer->name;
			$newDatum['No KTP'] = $customer->NIK;
			$newDatum['NPWP'] = $customer->NPWP;
			$newDatum['Alamat'] = $customer->address;
			$newDatum['Alamat Email'] = $customer->email;
			$newDatum['Dana Penempatan(Rp)'] = $datum['nominal'];
			$accumulator += $datum['nominal'];
			$newDatum['Jk Waktu Investasi'] = $datum['MGI_month'];
			$newDatum['Nama Agent'] = $agent->name;
			$newDatum['Kode Agent'] = $agent->agent_code;
			$newDatum['Nama Leader'] = ($parent==null)?'-':$parent->name;
			$newDatum['Kode Leader'] = ($parent==null)?'-':$parent->agent_code;
			$datum = $newDatum;
        }
		$newDatum['No'] = '';
		$newDatum['Nama Investor'] = '';
		$newDatum['No KTP'] = '';
		$newDatum['NPWP'] = '';
		$newDatum['Alamat'] = '';
		$newDatum['Alamat Email'] = 'Total';
		$newDatum['Dana Penempatan(Rp)'] = $accumulator;
		$newDatum['Jk Waktu Investasi'] ='';
		$newDatum['Nama Agent'] ='';
		$newDatum['Kode Agent'] = '';
		$newDatum['Nama Leader'] = '';
		$newDatum['Kode Leader'] = '';
		$file->data[] = $newDatum;
        // work on the exportAgentsExcelExport
        return $file->sheet('sales', function($sheet) use($file)
        {
			$columnCount = count($file->data[0]);
            $sheet->cells('A1:'.chr(64+$columnCount).'1', function($cells) {
                //$cells->setFontSize(8);
                $cells->setFontWeight('bold');
				$cells->setBackground('#CCCCCC');
            });
			$sheet->setBorder('A1:'.chr(64+$columnCount).strval(sizeof($file->data)+1),'solid');
			$sheet->setFontSize(10);
			if(config('global.export_type') == 'pdf'){$sheet->setOrientation('landscape');}
            $sheet->fromArray($file->data, null, 'A1', true, true);
			$sheet->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
			$sheet->cells('A'.strval(sizeof($file->data)+1).':E'.strval(sizeof($file->data)+1), function($cells) {
                //$cells->setFontSize(8);
				$cells->setBackground('#AAAAAA');
            });
			$sheet->cells('H'.strval(sizeof($file->data)+1).':L'.strval(sizeof($file->data)+1), function($cells) {
                //$cells->setFontSize(8);
				$cells->setBackground('#AAAAAA');
            });
        })->export(config('global.export_type'));
    }

}

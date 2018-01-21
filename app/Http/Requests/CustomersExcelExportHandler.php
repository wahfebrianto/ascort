<?php

namespace App\Http\Requests;

use Maatwebsite\Excel\Files\ExportHandler;

class CustomersExcelExportHandler implements ExportHandler {

	public static function _rename_arr_key($oldkey, $newkey, array &$arr) {
		if (array_key_exists($oldkey, $arr)) {
			$arr[$newkey] = $arr[$oldkey];
			unset($arr[$oldkey]);
			return TRUE;
		} else {
			return FALSE;
		}
	}
    public function handle($file)
    {
		$ctr=1;
        // change all data that need to be formatted
        foreach($file->data as &$datum) {
            foreach ($datum as $key => &$value) {
                if($key == 'gender') {
                    $value = trans('general.gender.' . $value);
                } elseif($key == 'DOB') {
                    $value = date_format(date_create($value),'m/d/Y');
                } elseif($key == 'id_card_expiry_date') {
                    $value = date_format(date_create($value),'m/d/Y');
                }
				else if($key == 'branch_office_id')
				{
					$value = \App\BranchOffice::getBranchOfficeFromId($datum['branch_office_id'])->branch_name;
				}
            }
			$datum = array_merge(array('No'=>$ctr++),$datum);
			$renameColumn = array(
				'name'=>'Nama',
				'NIK'=>'No Indentitas',
				'NPWP' =>'NPWP',
				'address' => 'Alamat',
				'email' => 'Email',
				'phone_number' => 'Nomor Telepon',
				'handphone_number' => 'Nomor Handphone',
				'gender' => 'Jenis Kelamin',
				'state' => 'Provinsi',
				'city'	=>	'Kota',
				'zipcode'	=> 'Kode Pos',
				'cor_address'	=>	'Alamat Korespondensi',
				'cor_state'	=>	'Provinsi Korespondensi',
				'cor_city'	=> 'Kota Korespondensi',
				'cor_zipcode' => 'Kode Pos Korespondensi',
				'last_transaction' => 'Transaksi Terakhir',
				'branch_office_id' => 'Kantor Cabang'
			);
			foreach($renameColumn as $key => $valuee){
				CustomersExcelExportHandler::_rename_arr_key($key,$valuee,$datum);
			}
        }
		
        // work on the exportAgentsExcelExport
        return $file->sheet('customers', function($sheet) use($file)
        {
			//dd($file->data);
			$columnCount = count($file->data[0]);
            $sheet->cells('1', function($cells) {
                $cells->setFontSize(12);
                $cells->setFontWeight('bold');
            });
			$sheet->setBorder('A1:'.chr(64+$columnCount).strval(sizeof($file->data)+1),'solid');
			$sheet->setFontSize(12);
			if(config('global.export_type') == 'pdf'){$sheet->setOrientation('landscape');}
            $sheet->fromArray($file->data, null, 'A1', true, true);
			$sheet->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
			$sheet->cells('A1:'.chr(64+$columnCount).'1', function($cells) {
                //$cells->setFontSize(8);
				$cells->setBackground('#AAAAAA');
				$cells->setAlignment('center');
            });
        })->export(config('global.export_type'));
    }

}

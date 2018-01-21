<?php
namespace App\Http\Requests;
use Maatwebsite\Excel\Files\ExportHandler;

class AgentsCommissionExcelExportHandler implements ExportHandler {

    public function handle($file)
    {
		$backupdata = $file->data;
		$emptyrow = array('-'=>'');
		$newdata = array();
		$acctps = 0 ;
		$accor = 0 ;
		$commissionPercentage = array(30,50,70,90,0);
		for($i=0;$i<count($backupdata[1]);$i++)
		{
			$totalPersonalSelling = 0;
			$indNow = 4;
			for($j=0;$j<count($backupdata[1][$i]);$j++){
				$agent = $backupdata[1][$i][$j];
				if($j == 0 ) //first agent
				{
					$sales = $agent->sales()->whereBetween('MGI_start_date', [
						\DateTime::createFromFormat('d/m/Y', $backupdata[0][0]),
						\DateTime::createFromFormat('d/m/Y', $backupdata[0][1])
					])->get();
					foreach($sales as $sale){
						$totalPersonalSelling += 0.01*config('global.zpercentage')*($sale->nominal * ($sale->MGI_month/12));
					}
				}
				$or = 0;
				do{
					$or += (0.01 * $commissionPercentage[$indNow] * $totalPersonalSelling);
					//$or .= $commissionPercentage[$indNow]. ' ';
					if( $indNow > 0){
						$indNow--;
					}
					else{
						break;
					}
				}while($agent->agent_position()->get()[0]->level < $indNow+1);
				$currency = ($j>0)?0:$totalPersonalSelling;
				$acctps += $currency;
				$accor += $or;
				$newdata[] = array(
					'Kode Agen' => $agent->agent_code,
					'Tipe'	=>	$agent->type,
					'Nama Lengkap'=>$agent->name,
					'Email'	=>	$agent->email,
					'No Identitas'	=> $agent->NIK,
					'NPWP'	=> $agent->NPWP,
					'Jabatan'	=> $agent->agent_position()->get()[0]->name,
					'Total Komisi Personal Selling (IDR)' => $currency>0?number_format($currency, 2):'-',
					'Total OR Leader (IDR)' => $or>0?number_format($or,2):'-',
					'Total Sebelum Pajak (IDR)' => number_format($currency+$or,2),
					'Nama Bank'	=> $agent->bank,
					'Cabang'	=> $agent->bank_branch,
					'No. Rekening'	=>	$agent->account_number,
					'Atas Nama'	=> $agent->account_name
				);
			}
			$newdata[] = $emptyrow;
		}
		$newdata[] = array(
			'Kode Agen' => '',
			'Tipe'	=>	'',
			'Nama Lengkap'=>'',
			'Email'	=>	'',
			'No Identitas'	=> '',
			'NPWP'	=>'',
			'Jabatan'	=> 'Sub Total Pembayaran',
			'Total Komisi Personal Selling (IDR)' => number_format($acctps,2),
			'Total OR Leader (IDR)' => number_format($accor,2),
			'Total Sebelum Pajak (IDR)' => number_format($acctps+$accor,2),
			'Nama Bank'	=> '',
			'Cabang'	=> '',
			'No. Rekening'	=>	'',
			'Atas Nama'	=> ''
		);
		//dd($newdata);
		$file->data = $newdata;
		return $file->sheet('agents', function($sheet) use($file)
        {
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
			$sheet->cells('A'.strval(sizeof($file->data)+1).':F'.strval(sizeof($file->data)+1), function($cells) {
                //$cells->setFontSize(8);
				$cells->setBackground('#AAAAAA');
            });
			$sheet->cells('K'.strval(sizeof($file->data)+1).':N'.strval(sizeof($file->data)+1), function($cells) {
                //$cells->setFontSize(8);
				$cells->setBackground('#AAAAAA');
            });
        })->export(config('global.export_type'));
    }

}
<?php
namespace App\Http\Requests;
use Maatwebsite\Excel\Files\ExportHandler;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Input;

use App\BranchOffice;
use App\Agent;
use App\Money;
use App\BoardOfDirector;
use App\Calculations\Overriding;

class RecapExcelExportHandler implements ExportHandler
{

    public function handle($file)
    {
        $ctr = 1;
        // change all data that need to be formatted
        $dataSheet1 = $this->getSheetData1($file->data);
        $dataSheet3 = $this->getSheetData3($file->data);
        $dataSheet2 = $this->getSheetData2($dataSheet3[0]);
        $dataSheet4 = $this->getSheetData4();
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');

        Excel::create('recap_' . $start_date . '_' . $end_date . '_' . date('YmdHis'), function($file) use($dataSheet1, $dataSheet2, $dataSheet3, $dataSheet4, $end_date){

            // Our first sheet
            $file->sheet('Summary Komisi', function($sheet) use($dataSheet1)
            {

            });

            // Our second sheet
            $file->sheet('Format Komisi Final', function($sheet) use($dataSheet2) {
                //dd($dataSheet2);
                $sheet->mergeCells('A2:K2');
                $sheet->mergeCells('A3:K3');
                $sheet->cell('A2',function($cell){
                    $cell->setValue('REKAPITULASI KOMISI AGEN & MITRA USAHA');
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('A3',function($cell){
                    $cell->setValue('SBIJP ASCORT PREMIER');
                    $cell->setFontWeight('bold');
                });
                $sheet->setFontSize(12);
                $sheet->fromArray($dataSheet2[0], null, 'A5', true, true);
                $sheet->data = [];
                $rowCount= count($dataSheet2[0]);
                $sheet->mergeCells('A'.($rowCount+5).':E'.($rowCount+5));
                $sheet->mergeCells('F'.($rowCount+5).':H'.($rowCount+5));
                $sheet->mergeCells('L'.($rowCount+5).':O'.($rowCount+5));

                $sheet->cell('I'.($rowCount+5),function($cell) use($dataSheet2){
                  $cell->setValue($dataSheet2[2]);
                });
                $sheet->cell('J'.($rowCount+5),function($cell) use($dataSheet2){
                  $cell->setValue($dataSheet2[3]);
                });
                $sheet->cell('K'.($rowCount+5),function($cell) use($dataSheet2){
                  $cell->setValue($dataSheet2[2]+$dataSheet2[3]);
                });
                $sheet->cell('F'.($rowCount+5),function($cell) use($dataSheet2){
                  $cell->setValue('Sub Total Pembayaran');
                });
                $sheet->cells($rowCount+5,function($cells){
                  $cells->setFontWeight('bold');
                });
                $totalRow = $rowCount+5+count($dataSheet2[1]);
                $sheet->mergeCells('I'.($rowCount+6).':J'.$totalRow);
                $sheet->fromArray($dataSheet2[1], null, 'A'.($rowCount+6), true, false);
                $sheet->mergeCells('A'.($totalRow+1).':E'.($totalRow+1));
                $sheet->mergeCells('F'.($totalRow+1).':H'.($totalRow+1));
                $sheet->cell('F'.($totalRow+1),function($cell) use($dataSheet2){
                  $cell->setValue('TOTAL PEMBAYARAN');
                });
                $sheet->cell('I'.($totalRow+1),function($cell) use($dataSheet2){
                  $cell->setValue($dataSheet2[2]);
                });
                $sheet->cell('J'.($totalRow+1),function($cell) use($dataSheet2){
                  $cell->setValue($dataSheet2[3]);
                });
                $sheet->cell('K'.($totalRow+1),function($cell) use($dataSheet2,$rowCount,$totalRow){
                  $cell->setValue('=SUM(K'.($rowCount+5).':K'.($totalRow).')');
                });
                $sheet->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
            });

            $file->sheet('Rekap Mingguan Investor Baru', function($sheet) use($dataSheet3, $end_date){
              $tabelKota = $dataSheet3[1];
              $dataSheet3 = $dataSheet3[0];
              $columnCount = count($dataSheet3[0]);
              $sheet->cells('1', function($cells)
              {
                  $cells->setFontSize(12);
                  $cells->setFontWeight('bold');
              });
              $sheet->setBorder('A4:' . chr(64 + $columnCount) . strval(sizeof($dataSheet3) + 4), 'thin');

              $sheet->mergeCells('A1:M1');
              $sheet->mergeCells('A2:M2');
              $sheet->cell('A1',function($cell){
                    $cell->setValue('Rekap Mingguan - Investor Baru Ascort Premier SERI - ');
                    $cell->setFontWeight('bold');
                });
              $sheet->cell('A2',function($cell) use($end_date){
                    $cell->setValue('Periode : ' . Carbon::createFromFormat('d/m/Y', $end_date)->format('d F Y'));
                    $cell->setFontWeight('bold');
                });
              $sheet->fromArray($dataSheet3, null, 'A4', true, true);
              $sheet->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
              $sheet->cells('A4:' . chr(64 + $columnCount) . '4', function($cells)
              {
                  $cells->setBackground('#AAAAAA');
                  $cells->setFontWeight('bold');
                  $cells->setAlignment('center');
              });
              $sheet->cells('A5:' . chr(64 + $columnCount) . (count($dataSheet3) + 4), function($cells)
              {
                  $cells->setAlignment('center');
              });
              $sheet->cells('G5:' . 'G' . (count($dataSheet3) + 4), function($cells)
              {
                  $cells->setAlignment('right');
              });
              $sheet->setFreeze('C5');

              $sheet->cells((count($dataSheet3) + 6), function($cells)
              {
                  $cells->setFontSize(14);
                  $cells->setFontWeight('bold');
                  $cells->setBackground('#808080');
              });

              $sheet->setColumnFormat(array(
                  'C5:C' . (count($dataSheet3) + 4) => '@',
                  'D5:D' . (count($dataSheet3) + 4) => '@',
                  'G5:G' . (count($dataSheet3) + 4) => '#,##0'
              ));

              $sheet->cell('E' . (count($dataSheet3) + 6),function($cells){
                    $cells->setValue('TOTAL');
                    $cells->setBackground('#FFFFFF');
                    $cells->setAlignment('center');
              });

              $sheet->cell('F' . (count($dataSheet3) + 6),function($cells) use($dataSheet3){
                    $cells->setValue('=sum(G5:G' . (count($dataSheet3) + 4) . ')');
                    $cells->setBackground('#FFFFFF');
                    $cells->setAlignment('center');
              });

              $sheet->setColumnFormat(array(
                  'F' . (count($dataSheet3) + 6) => '#,##0'
              ));

              $sheet->setBorder('A' . (count($dataSheet3) + 6) . ':' . chr(64 + $columnCount) . (count($dataSheet3) + 6), 'thin');

              $sheet->data = [];

              $sheet->fromArray($tabelKota, null, 'B' . (count($dataSheet3) + 9), true, true);

              $sheet->cells('B' . (count($dataSheet3) + 9) . ':D' . (count($dataSheet3) + 9), function($cells)
              {
                  $cells->setBackground('#AAAAAA');
                  $cells->setFontWeight('bold');
              });

              $sheet->cells('B' . (count($dataSheet3) + 9) . ':D' . (count($dataSheet3) + 9), function($cells)
              {
                  $cells->setAlignment('center');
              });

              $sheet->cells('C' . (count($dataSheet3) + 9) . ':D' . (count($dataSheet3) + 9), function($cells)
              {
                  $cells->setBackground('#AAAAAA');
                  $cells->setFontWeight('bold');
              });

              $sheet->cells('B' . (count($dataSheet3) + 9) . ':D' . (count($dataSheet3) + 10 + count($tabelKota)), function($cells)
              {
                  $cells->setFontSize(14);
                  $cells->setAlignment('center');
              });

              $sheet->cells('D' . (count($dataSheet3) + 10) . ':D' . (count($dataSheet3) + 10 + count($tabelKota)), function($cells)
              {
                  $cells->setAlignment('right');
              });

              $sheet->cell('B' . (count($dataSheet3) + 10 + count($tabelKota)),function($cells){
                    $cells->setValue('TOTAL');
                    $cells->setBackground('#FFFF00');
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
              });

              $sheet->cell('C' . (count($dataSheet3) + 10 + count($tabelKota)),function($cells) use($dataSheet3, $tabelKota){
                    $cells->setValue('=sum(C' . (count($dataSheet3) + 10) . ':C' . (count($dataSheet3) + 9 + count($tabelKota)) . ')');
                    $cells->setBackground('#FFFF00');
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
              });

              $sheet->cell('D' . (count($dataSheet3) + 10 + count($tabelKota)),function($cells) use($dataSheet3, $tabelKota){
                    $cells->setValue('=sum(D' . (count($dataSheet3) + 10) . ':D' . (count($dataSheet3) + 9 + count($tabelKota)) . ')');
                    $cells->setBackground('#FFFF00');
                    $cells->setAlignment('right');
                    $cells->setFontWeight('bold');
              });

              $sheet->setBorder('B' . (count($dataSheet3) + 9) . ':D' . (count($dataSheet3) + 10 + count($tabelKota)), 'thin');

              $sheet->setColumnFormat(array(
                  'D' . (count($dataSheet3) + 10) . ':D' . (count($dataSheet3) + 10 + count($tabelKota)) => '#,##0'
              ));

            });

            $file->sheet('Rekap Data Agen', function($sheet) use($dataSheet4){
                $columnCount = count($dataSheet4);
                $sheet->mergeCells('A5:D5');
                $sheet->cell('A5',function($cell){
                    $cell->setValue('REKAP DATA AGEN SERI');
                    $cell->setFontWeight('bold');
                });
                $sheet->setFontSize(12);
                $sheet->fromArray($dataSheet4, null, 'A7', true, true);
                $sheet->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
            });

        })->export('xls');

        return redirect()->back();
    }

    private function getSheetData1($data)
    {

    }

    private function getSheetData2($data)
    {
        $list_ = array();
        $ctr=1;
        $emptyrow = array('-'=>'');
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        $commissionPercentage = array(30,50,70,90,0);
        $acctps = 0;
        $accor = 0;
        $agents = array();
        $nominal = array();
        foreach($data as $datum){
          $agent = Agent::with('agent_position')
          ->where('agent_code',$datum['Agent Code'])->first();
          $agents[] = $agent;
          $norm_nom = str_replace('Rp ','',$datum['Dana Penempatan (Rp)']);
          $norm_nom = str_replace(',00', '', $norm_nom);
          $norm_nom = str_replace('.','',$norm_nom);
          $nominal[] = intval($norm_nom);
        }
        foreach($agents as $agent){
          $threeTopAgent = array($agent);
          $now = $agent;
          for($i=0;$i<3;$i++){
            if($now->hasParent()){
              $threeTopAgent[] = $now->getParent();
              $now = $now->getParent();
            }
            else{
              break;
            }
          }
          $list_[] = $threeTopAgent;
        }
        $backupdata = $list_;
        $emptyrow = array('-'=>'');
        $newdata = array();
        $acctps = 0 ;
        $accor = 0 ;
        $commissionPercentage = array(30,50,70,90,0);
        for($i=0;$i<count($backupdata);$i++)
        {
          $totalPersonalSelling = 0;
          $indNow = 4;
          for($j=0;$j<count($backupdata[$i]);$j++){
            $agent = $backupdata[$i][$j];
            if($j == 0 ) //first agent
            {
              $sales = $agent->sales()->whereBetween('MGI_start_date', [
                \DateTime::createFromFormat('d/m/Y', $start_date),
                \DateTime::createFromFormat('d/m/Y', $end_date)
              ])->get();
              foreach($sales as $sale){
                $totalPersonalSelling += 0.01*config('global.zpercentage')*($nominal[$i] * ($sale->MGI_month/12));
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
              'No' => $ctr++,
              'Kode Agen' => $agent->agent_code,
              'Tipe'  =>  $agent->type,
              'Nama Lengkap'=>$agent->name,
              'Email Address'=>$agent->email,
              'No Identitas'  => $agent->NIK,
              'NPWP'  => $agent->NPWP,
              'Jabatan' => $agent->agent_position()->get()[0]->name,
              'Total Komisi Personal Selling (IDR)' => \App\Money::format('%(.2n', $currency),
              'Total OR Leader (IDR)' => \App\Money::format('%(.2n', $or),
              'Total Sebelum Pajak (IDR)' => \App\Money::format('%(.2n', $currency+$or),
              'Nama Bank' => $agent->bank,
              'Cabang'  => $agent->bank_branch,
              'No. Rekening'  =>  $agent->account_number,
              'Atas Nama' => $agent->account_name
            );
          }
          $newdata[] = $emptyrow;
        }
        $bods = BoardOfDirector::where('is_active',1)->get();
        $bod_ = array();
        $ctr= 1;
        foreach($bods as $bod){
          $agent = Agent::where('NPWP',$bod->NPWP)->first();
          $newRow = array(
            'No' => $ctr++,
            'Kode Agen' => (is_null($agent)?'':$agent->agent_code),
            'Tipe' => $bod->type,
            'Nama Lengkap' => $bod->bod_name,
            'Email Address' => $bod->email,
            'No. Identitas'=> $bod->identity_number,
            'NPWP' => $bod->NPWP,
            'Jabatan' => $bod->position,
            'Total Komisi Personal Selling (IDR)' => '',
            'Total OR Leader (IDR)' => '',
            'Total Sebelum Pajak (IDR)' => '',
            'Nama Bank' => $bod->bank,
            'Cabang'  => $bod->bank_branch,
            'No. Rekening'  =>  $bod->account_number,
            'Atas Nama' => $bod->account_name
          );
          $bod_[] = $newRow;
        }
        //$result = array_merge($newdata,$bod_);
        return array($newdata,$bod_,$acctps,$accor);
    }

    private function getSheetData3($data)
    {
      $result[0] = [];
      $result[1] = [];
      foreach (\App\BranchOffice::all()->toArray() as $SO) {
        $newSO = [];
        $newSO['Sales Office'] = $SO['branch_name'];
        $newSO['Kota'] = $SO['city'];
        $newSO['Banyak Investor'] = 0;
        $newSO['Nominal'] = 0;
        $result[1][] = $newSO;
      }

      $ctr = 1;

      foreach ($data as $agent) {
        foreach ($agent->sales as $sale) {
          $rowData = [];
          $rowData["No"] = $ctr;
          $rowData["Nama Investor"] = $sale->customer_name;
          $rowData["No KTP"] = $sale->customer->NIK;
          $rowData["NPWP"] = ($sale->customer->NPWP == 0)?'NA':$sale->customer->NPWP;
          $rowData["Alamat"] = $sale->customer->address . ', ' . $sale->customer->city;
          $rowData["Email Address"] = ($sale->customer->email == '')?'NA':$sale->customer->email;
          $rowData["Dana Penempatan (Rp)"] = $sale->nominal;
          $rowData["Jk Waktu Investasi"] = $sale->MGI;
          $rowData["Nama Agent"] = $agent->name;
          $rowData["Agent Code"] = $agent->agent_code;
          $rowData["Nama Leader"] = ($agent->parent == NULL)?'NA':$agent->parent->name;
          $rowData["Leader Code"] = ($agent->parent == NULL)?'NA':$agent->parent->agent_code;
          $rowData["Bilyet dikirim ke:"] = '';
          $rowData["Tgl Transfer"] = Carbon::createFromFormat('d/m/Y', $sale->MGI_start_date)->format('d-M-y');
          $result[0][] = $rowData;
          $ctr++;

          $nemu = false;
          foreach ($result[1] as &$SO) {
            if($SO['Kota'] == $sale->customer->city)
            {
              $SO['Banyak Investor']++;
              $SO['Nominal'] += $sale->nominal;
              $nemu = true;
              break;
            }
          }
          if(!$nemu)
          {
            foreach ($result[1] as &$SO) {
              if($SO['Kota'] == $agent->branchOffice->city)
              {
                $SO['Banyak Investor']++;
                $SO['Nominal'] += $sale->nominal;
                break;
              }
            }
          }
        }
      }
      foreach ($result[1] as &$SO) {
        unset($SO['Kota']);
      }
      return $result;
    }

    private function getSheetData4()
    {
        $start_date = Carbon::createFromFormat('d/m/Y',Input::get('start_date'));
        $end_date = Carbon::createFromFormat('d/m/Y',Input::get('end_date'));
        $agents = Agent::where('join_date','<=',$end_date)
        ->where('join_date','>=',$start_date)
        ->where('is_active',1)
        ->get();
        $ctr=1;
        $newData = array();
        foreach($agents as $agent){
            $rowData = array();
            $rowData['No.'] = $ctr;
            $rowData['Kode Agen'] = $agent->agent_code;
            $rowData['Tipe'] = $agent->type;
            $rowData['Sales Office'] = $agent->branchOffice->branch_name;
            $rowData['Nama Lengkap'] = $agent->name;
            $rowData['No. Identitas'] = $agent->NIK;
            $rowData['NPWP'] = $agent->NPWP;
            $rowData['Jabatan'] = $agent->agent_position->name;
            $rowData['Email'] = $agent->email;
            $newData[] = $rowData;
            $ctr++;
        }
        return $newData;
    }
}

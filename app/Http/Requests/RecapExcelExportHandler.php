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
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');
        // change all data that need to be formatted
        $dataSheet3 = $this->getSheetData3($file->data);
        if(count($dataSheet3[0]) <= 0)
        {
          \Flash::error("Recapitulation report for period $start_date until $end_date is not available.");
          return redirect()->back();
        }
        $dataSheet2 = $this->getSheetData2($dataSheet3);
        $dataSheet1 = $this->getSheetData1($dataSheet2);
        $dataSheet4 = $this->getSheetData4();

        Excel::create('recap_' . $start_date . '_' . $end_date . '_' . date('YmdHis'), function($file) use($dataSheet1, $dataSheet2, $dataSheet3, $dataSheet4, $end_date){

            // Our first sheet
            $file->sheet('Summary Komisi', function($sheet) use($dataSheet1)
            {
                $sheet->mergeCells('A1:D1');
                $sheet->cell('A1',function($cell){
                    $cell->setValue('REKAP SUMMARY KOMISI AGEN SERI - ');
                    $cell->setFontWeight('bold');
                    $cell->setFontSize(16);
                });
                $sheet->setFontSize(11);
                $sheet->fromArray($dataSheet1[0], null, 'A3', true, true);
                $sheet->data = [];
                $rowCount= count($dataSheet1[0]) + 1;
                $sheet->mergeCells('A'.($rowCount+3).':F'.($rowCount+3));
                $sheet->mergeCells('J'.($rowCount+3).':M'.($rowCount+3));
                $sheet->mergeCells('G'.($rowCount+3).':H'.($rowCount+3));
                $sheet->cell('G'.($rowCount+3),function($cell) use($dataSheet1){
                  $cell->setValue('SUB TOTAL');
                  $cell->setFontSize(14);
                  $cell->setFontWeight('bold');
                });
                $sheet->cell('I'.($rowCount+3),function($cell) use($dataSheet1){
                  $cell->setValue($dataSheet1[3]+$dataSheet1[2]);
                  $cell->setFontSize(14);
                  $cell->setFontWeight('bold');
                });
                $totalRow = $rowCount+3+count($dataSheet1[1]);

                $sheet->fromArray($dataSheet1[1], null, 'A'.($rowCount+4), true, false);
                $sheet->mergeCells('A'.($totalRow+1).':F'.($totalRow+1));
                $sheet->mergeCells('G'.($totalRow+1).':H'.($totalRow+1));
                $sheet->mergeCells('J'.($totalRow+1).':M'.($totalRow+1));
                $sheet->cell('G'.($totalRow+1),function($cell) use($dataSheet1){
                  $cell->setValue('TOTAL');
                  $cell->setFontSize(14);
                  $cell->setFontWeight('bold');
                });
                $sheet->cell('I'.($totalRow+1),function($cell) use($dataSheet1,$rowCount,$totalRow){
                  $cell->setValue('=SUM(I'.($rowCount+3).':I'.($totalRow).')');
                  $cell->setFontSize(14);
                  $cell->setFontWeight('bold');
                });
                $sheet->cell('G'.($totalRow+3),function($cell) use($dataSheet1){
                  $cell->setValue('Control Cost 8%');
                  $cell->setFontSize(12);
                  $cell->setFontWeight('bold');
                });
                $sheet->cell('I'.($totalRow+3),function($cell) use($dataSheet1,$rowCount,$totalRow){
                  $cell->setValue($dataSheet1[4]);
                  $cell->setFontSize(12);
                  $cell->setFontWeight('bold');
                });
                $sheet->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);

                $sheet->cells('A3:M' . ($totalRow + 1), function($cells)
                {
                    $cells->setAlignment('center');
                });

                $sheet->cells('D4:' . 'D' . ($totalRow + 3), function($cells)
                {
                    $cells->setAlignment('left');
                });

                $sheet->cells('E4:' . 'E' . ($totalRow + 3), function($cells)
                {
                    $cells->setAlignment('left');
                });

                $sheet->cells('I4:' . 'I' . ($totalRow + 3), function($cells)
                {
                    $cells->setAlignment('right');
                });

                $sheet->cells('A' . ($rowCount + 3), function($cells)
                {
                    $cells->setBackground('#D9D9D9');
                });
                $sheet->cells('G' . ($rowCount + 3) . ':I' . ($rowCount + 3), function($cells)
                {
                    $cells->setBackground('#FFFF00');
                });
                $sheet->cells('J' . ($rowCount + 3), function($cells)
                {
                    $cells->setBackground('#D9D9D9');
                });

                $sheet->cells('A' . ($totalRow + 1), function($cells)
                {
                    $cells->setBackground('#D9D9D9');
                });
                $sheet->cells('G' . ($totalRow + 1) . ':I' . ($totalRow + 1), function($cells)
                {
                    $cells->setBackground('#FFFF00');
                });
                $sheet->cells('J' . ($totalRow + 1), function($cells)
                {
                    $cells->setBackground('#D9D9D9');
                });

                $sheet->setColumnFormat(array(
                    'I4:K' . ($totalRow + 3) => '#,##0'
                ));

                $sheet->cells('A3:M3', function($cells)
                {
                    $cells->setBackground('#DAEEF3');
                    $cells->setFontWeight('bold');
                    $cells->setValignment('center');
                });

                $sheet->setHeight(3, 45);
                $sheet->setBorder('A3:M' . ($totalRow + 1), 'thin');
            });

            // Our second sheet
            $file->sheet('Format Komisi Final', function($sheet) use($dataSheet2, $end_date) {
                //dd($dataSheet2);
                $sheet->setFontSize(11);
                $sheet->mergeCells('A1:K1');
                $sheet->mergeCells('A2:K2');
                $sheet->cell('A1',function($cell){
                    $cell->setValue('REKAPITULASI KOMISI AGEN & MITRA USAHA');
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('A2',function($cell){
                    $cell->setValue('SBIJP ASCORT PREMIER - ');
                    $cell->setFontWeight('bold');
                });
                $sheet->fromArray($dataSheet2[0], null, 'A4', true, true);
                $sheet->data = [];
                $rowCount= count($dataSheet2[0]);
                $sheet->mergeCells('A'.($rowCount+4).':E'.($rowCount+4));
                $sheet->cells('A'.($rowCount+4), function($cells)
                {
                    $cells->setBackground('#A6A6A6');
                });
                $sheet->mergeCells('F'.($rowCount+4).':H'.($rowCount+4));
                $sheet->mergeCells('L'.($rowCount+4).':O'.($rowCount+4));
                $sheet->cells('L'.($rowCount+4), function($cells)
                {
                    $cells->setBackground('#A6A6A6');
                });

                $sheet->cell('I'.($rowCount+4),function($cell) use($dataSheet2){
                  $cell->setValue($dataSheet2[2]);
                  $cell->setFontWeight('bold');
                  $cell->setValignment('center');
                  $cell->setFontSize(12);
                });
                $sheet->cell('J'.($rowCount+4),function($cell) use($dataSheet2){
                  $cell->setValue($dataSheet2[3]);
                  $cell->setFontWeight('bold');
                  $cell->setValignment('center');
                  $cell->setFontSize(12);
                });
                $sheet->cell('K'.($rowCount+4),function($cell) use($dataSheet2){
                  $cell->setValue($dataSheet2[2]+$dataSheet2[3]);
                  $cell->setFontWeight('bold');
                  $cell->setValignment('center');
                  $cell->setFontSize(12);
                });
                $sheet->cell('F'.($rowCount+4),function($cell) use($dataSheet2){
                  $cell->setValue('Sub Total Pembayaran');
                  $cell->setFontWeight('bold');
                  $cell->setValignment('center');
                  $cell->setFontSize(12);
                });
                $totalRow = $rowCount+4+count($dataSheet2[1]);
                $sheet->mergeCells('I'.($rowCount+5).':J'.$totalRow);
                $sheet->cells('I'.($rowCount+5), function($cells)
                {
                    $cells->setBackground('#D9D9D9');
                });
                $sheet->fromArray($dataSheet2[1], null, 'A'.($rowCount+5), true, false);
                $sheet->mergeCells('A'.($totalRow+1).':E'.($totalRow+1));
                $sheet->cells('A'.($totalRow+1), function($cells)
                {
                    $cells->setBackground('#808080');
                });
                $sheet->cells('L'.($totalRow+1).':O'.($totalRow+1), function($cells)
                {
                    $cells->setBackground('#808080');
                });
                $sheet->mergeCells('F'.($totalRow+1).':H'.($totalRow+1));
                $sheet->cell('F'.($totalRow+1),function($cell) use($dataSheet2){
                  $cell->setValue('TOTAL PEMBAYARAN');
                  $cell->setFontWeight('bold');
                  $cell->setValignment('center');
                  $cell->setAlignment('center');
                  $cell->setFontSize(12);
                });
                $sheet->cell('I'.($totalRow+1),function($cell) use($dataSheet2){
                  $cell->setValue($dataSheet2[2]);
                  $cell->setFontWeight('bold');
                  $cell->setValignment('center');
                  $cell->setFontSize(12);
                });
                $sheet->cell('J'.($totalRow+1),function($cell) use($dataSheet2){
                  $cell->setValue($dataSheet2[3]);
                  $cell->setFontWeight('bold');
                  $cell->setValignment('center');
                  $cell->setFontSize(12);
                });

                $sheet->cell('K'.($totalRow+1),function($cell) use($dataSheet2,$rowCount,$totalRow){
                  $cell->setValue('=SUM(K'.($rowCount+4).':K'.($totalRow).')');
                  $cell->setFontWeight('bold');
                  $cell->setValignment('center');
                  $cell->setFontSize(12);
                });
                $sheet->mergeCells('F'.($totalRow+2).':H'.($totalRow+2));
                $sheet->mergeCells('I'.($totalRow+2).':J'.($totalRow+2));
                $sheet->cell('K'.($totalRow+2),function($cell) use($dataSheet2,$rowCount,$totalRow){
                  $cell->setValue($dataSheet2[4]);
                  $cell->setFontColor('#FF0000');
                });
                $sheet->cell('F'.($totalRow+2),function($cell) use($dataSheet2,$rowCount,$totalRow){
                  $cell->setValue('Total Budget Control');
                  $cell->setFontColor('#FF0000');
                  $cell->setAlignment('center');
                });
                $sheet->cell('I'.($totalRow+2),function($cell) use($dataSheet2,$rowCount,$totalRow){
                  $cell->setValue('8% of Total Sales');
                  $cell->setFontColor('#FF0000');
                  $cell->setAlignment('center');
                });

                $sheet->cell('K'.($totalRow+3),function($cell) use($dataSheet2,$rowCount,$totalRow){
                  $cell->setValue($dataSheet2[5]);
                  $cell->setFontWeight('bold');
                });
                $sheet->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);

                $sheet->cells('A4:O' . ($rowCount + 4), function($cells)
                {
                    $cells->setAlignment('center');
                });

                $sheet->cells('D5:' . 'D' . ($rowCount + 4), function($cells)
                {
                    $cells->setAlignment('left');
                });

                $sheet->cells('E5:' . 'E' . ($rowCount + 4), function($cells)
                {
                    $cells->setAlignment('left');
                });

                $sheet->cells('I5:' . 'I' . ($rowCount + 4), function($cells)
                {
                    $cells->setAlignment('right');
                });

                $sheet->cells('J5:' . 'J' . ($rowCount + 4), function($cells)
                {
                    $cells->setAlignment('right');
                });

                $sheet->cells('K5:' . 'K' . ($rowCount + 4), function($cells)
                {
                    $cells->setAlignment('right');
                });

                $sheet->cells('A4:O4', function($cells)
                {
                    $cells->setBackground('#DAEEF3');
                    $cells->setFontWeight('bold');
                    $cells->setValignment('center');
                });

                $sheet->cells('A' . ($rowCount + 5) . ':O' . $totalRow, function($cells)
                {
                    $cells->setAlignment('center');
                });

                $sheet->cells('D' . ($rowCount + 5) . ':D' . $totalRow, function($cells)
                {
                    $cells->setAlignment('left');
                });

                $sheet->cells('E' . ($rowCount + 5) . ':E' . $totalRow, function($cells)
                {
                    $cells->setAlignment('left');
                });

                $sheet->cells('I' . ($rowCount + 5) . ':I' . $totalRow, function($cells)
                {
                    $cells->setAlignment('right');
                });

                $sheet->cells('J' . ($rowCount + 5) . ':J' . $totalRow, function($cells)
                {
                    $cells->setAlignment('right');
                });

                $sheet->cells('K' . ($rowCount + 5) . ':K' . $totalRow, function($cells)
                {
                    $cells->setAlignment('right');
                });

                $sheet->setColumnFormat(array(
                    'I5:K' . ($totalRow + 1) => '#,##0',
                    'K' . ($totalRow + 2) .':K' . ($totalRow + 3) => '#,##0'
                ));

                $sheet->mergeCells('A'.($totalRow+2).':E'.($totalRow+2));
                $sheet->cell('A'.($totalRow+2), function($cells)
                {
                    $cells->setValue('* Total Komisi/OR adalah perhitungan sebelum dipotong pajak, pajak akan dihitung oleh ASCORT sesuai ketentuan Pajak yang berlaku');
                    $cells->setFontSize(8);
                });

                $sheet->mergeCells('A'.($totalRow+3).':E'.($totalRow+3));
                $sheet->cell('A'.($totalRow+3), function($cells)
                {
                    $cells->setValue('** Bukti Potong Pajak harap di-email-kan langsung oleh RISCON ke para penerima saat pembayaran telah dilakukan');
                    $cells->setFontSize(8);
                });


                $sheet->cell('D'.($totalRow+7), function($cells)
                {
                    $cells->setValue('ASCORT Premier Group');
                    $cells->setFontSize(11);
                    $cells->setFontWeight('bold');
                });

                $sheet->cell('D'.($totalRow+9), function($cells)
                {
                    $cells->setValue('ttd');
                    $cells->setFontSize(8);
                });

                $sheet->cell('D'.($totalRow+11), function($cells)
                {
                    $cells->setValue('Robby Chandra');
                    $cells->setFontSize(11);
                });


                $sheet->cell('I'.($totalRow+6), function($cells) use($end_date)
                {
                    $cells->setValue('Jakarta, ' . Carbon::createFromFormat('d/m/Y', $end_date)->format('d/m/Y'));
                    $cells->setFontSize(11);
                    $cells->setAlignment('center');
                });

                $sheet->cell('I'.($totalRow+7), function($cells)
                {
                    $cells->setValue('PT ASCORT ASIA INTERNASIONAL');
                    $cells->setFontSize(11);
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->cell('I'.($totalRow+9), function($cells)
                {
                    $cells->setValue('Approved is Signed');
                    $cells->setFontSize(8);
                    $cells->setAlignment('center');
                });

                $sheet->cell('I'.($totalRow+11), function($cells)
                {
                    $cells->setValue('Anthony Soewandy');
                    $cells->setFontSize(11);
                    $cells->setAlignment('center');
                });

                $sheet->cell('I'.($totalRow+12), function($cells)
                {
                    $cells->setValue('Group CEO & President Director');
                    $cells->setFontSize(11);
                    $cells->setAlignment('center');
                });

                $sheet->setHeight(4, 45);
                $sheet->setHeight(($rowCount + 4), 22);
                $sheet->setHeight(($totalRow + 1), 22);

                $sheet->setBorder('A4:O' . ($totalRow + 1), 'thin');
                $sheet->setBorder('F' . ($totalRow + 2) . ':K' . ($totalRow + 2), 'thin');
                $sheet->setBorder('K' . ($totalRow + 3), 'thin');

                $sheet->setFreeze('E5');
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
                    $cell->setValue('Rekap Mingguan - Investor Baru SBI JP Ascort Premier SERI - ');
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
                $columnCount = count($dataSheet4[0]);
                $sheet->mergeCells('A1:D1');
                $sheet->cell('A1',function($cell){
                    $cell->setValue('REKAP DATA AGEN SERI - ');
                    $cell->setFontWeight('bold');
                });
                $sheet->setFontSize(12);
                $sheet->fromArray($dataSheet4, null, 'A3', true, true);
                $sheet->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);

                $sheet->cells('A3:' . chr(64 + $columnCount) . (count($dataSheet4) + 3), function($cells)
                {
                    $cells->setAlignment('center');
                });

                $sheet->cells('A3:' . chr(64 + $columnCount) . '3', function($cells)
                {
                    $cells->setBackground('#AAAAAA');
                    $cells->setFontWeight('bold');
                    $cells->setValignment('center');
                });

                $sheet->setHeight(3, 45);

                $sheet->setBorder('A3:' . chr(64 + $columnCount) . strval(sizeof($dataSheet4) + 3), 'thin');

                $sheet->setFreeze('A4');
            });

        })->export('xls');

        return redirect()->back();
    }

    private function getSheetData1($data)
    {
      $ctr=1;
      $newData = array();
      foreach($data[0] as $datum){
        if(isset($datum['Kode Agen'])){
          if(isset($newData[$datum['Kode Agen']])){
            $intmoney = $datum['TOTAL Sebelum Pajak (IDR)'] + $newData[$datum['Kode Agen']]['TOTAL Sebelum Pajak (IDR)'];
            $newData[$datum['Kode Agen']]['TOTAL Sebelum Pajak (IDR)'] = $intmoney;
          }else{
            $newData[$datum['Kode Agen']] = array(
              'No' => $ctr++,
              'Kode Agen' => $datum['Kode Agen'],
              'Tipe' => $datum['Tipe'],
              'Nama Lengkap' => $datum['Nama Lengkap'],
              'Email Address' => $datum['Email Address'],
              'No. Identitas' => $datum['No Identitas'],
              'NPWP' => $datum['NPWP'],
              'Jabatan' => $datum['Jabatan'],
              'TOTAL Sebelum Pajak (IDR)' => $datum['TOTAL Sebelum Pajak (IDR)'],
              'Nama Bank' => $datum['Nama Bank'],
              'Cabang' => $datum['Cabang'],
              'No. Rekening' => $datum['No. Rekening'],
              'Atas Nama' => $datum['Atas Nama']
            );
          }
        }
      }
      foreach($data[1] as &$bod){
        unset($bod['Total Komisi Personal Selling (IDR)']);
        unset($bod['Total OR Leader (IDR)']);
      }
      return array($newData,$data[1],$data[2],$data[3],$data[4]);
    }
    private static function money2int($str){
      $norm_nom = str_replace('Rp ','',$str);
      $norm_nom = str_replace(',00', '', $norm_nom);
      $norm_nom = str_replace('.','',$norm_nom);
      $norm_nom = str_replace('=','',$norm_nom);
      return intval($norm_nom);
    }
    private function getSheetData2($data1)
    {
        $data = $data1[0];
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
          $nominal[] = RecapExcelExportHandler::money2int($datum['Dana Penempatan (Rp)']);
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
              'Total Komisi Personal Selling (IDR)' => $currency,
              'Total OR Leader (IDR)' => $or,
              'TOTAL Sebelum Pajak (IDR)' => $currency+$or,
              'Nama Bank' => $agent->bank,
              'Cabang'  => $agent->bank_branch,
              'No. Rekening'  =>  $agent->account_number,
              'Atas Nama' => $agent->account_name
            );
          }
          $newdata[] = $emptyrow;
        }
        $newdata[] = $emptyrow;
        $bods = BoardOfDirector::where('is_active',1)->get();
        $bod_ = array();
        $ctr= 1;

        $totalNom = 0;
        foreach($data1[1] as $branch){
          $totalNom += $branch['Nominal'];
        }
        $budgetControl = (0.08*$totalNom*0.5);
        $mp_nomx2 = $budgetControl-($acctps+$accor);
        foreach($bods as $bod){
          $nom = -1;
          switch($bod->position){
            case 'HQ':
              $nom = $acctps;
            break;
            case 'Arranger':
              $nom = $acctps;
            break;
            case 'GM1':
              $nom = 0.09 * $acctps;
            break;
            case 'GM2':
              $nom = 0.01 * $acctps;
            break;
            case 'MP':
              $nom = 0.08*$totalNom*0.5;
            break;
            default:
              foreach($data1[1] as $branch){
                switch($bod->position){
                  case $branch['Sales Office']:
                    $nom = 0.01*$branch['Nominal']*0.5;
                  break;
                }
              }
            break;
          }
          if($nom == -1){
            foreach($data1[1] as $branch){
              switch($bod->position){
                case 'HQ':
                  $nom = $acctps;
                break;
                case 'Arranger':
                  $nom = $acctps;
                break;
                case 'GM1':
                  $nom = 0.09 * $acctps;
                break;
                case 'GM2':
                  $nom = 0.01 * $acctps;
                break;
                case 'MP':
                  $nom = 0.5*$budgetControl;
                break;
                default:
                  $nom = 0;
                break;
              }
            }
          }
          if($bod->position != 'MP'){
            $mp_nomx2 -= $nom;
          }
          $npwp2search = str_replace('-','',$bod->NPWP);
          $npwp2search = str_replace('.', '', $npwp2search);
          $npwp2search = str_replace('_', '', $npwp2search);
          $npwp2search = str_replace(',', '', $npwp2search);
          $npwp2search = str_replace(' ', '', $npwp2search);
          $agent = Agent::where('NPWP',$npwp2search)->first();
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
            'TOTAL Sebelum Pajak (IDR)' => ($bod->position == 'MP'?-1:$nom),
            'Nama Bank' => $bod->bank,
            'Cabang'  => $bod->bank_branch,
            'No. Rekening'  =>  $bod->account_number,
            'Atas Nama' => $bod->account_name
          );
          $bod_[] = $newRow;
        }
        foreach($bod_ as &$bod1){
          if($bod1['TOTAL Sebelum Pajak (IDR)'] == -1){
            $bod1['TOTAL Sebelum Pajak (IDR)'] = 0.5*$mp_nomx2;
          }
        }
        //$result = array_merge($newdata,$bod_);
        return array($newdata,$bod_,$acctps,$accor,$budgetControl,$mp_nomx2);
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
          $rowData["Dana Penempatan (Rp)"] = '=' . $sale->nominal;
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

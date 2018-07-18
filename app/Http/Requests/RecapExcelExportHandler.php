<?php
namespace App\Http\Requests;
use Maatwebsite\Excel\Files\ExportHandler;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Input;
use App\BranchOffice;

class RecapExcelExportHandler implements ExportHandler
{

    public function handle($file)
    {
        $ctr = 1;
        // change all data that need to be formatted
        $dataSheet1 = $this->getSheetData1($file->data);
        $dataSheet2 = $this->getSheetData2($file->data);
        $dataSheet3 = $this->getSheetData3($file->data);
        $dataSheet4 = $this->getSheetData4($file->data);

        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');

        Excel::create('recap_' . $start_date . '_' . $end_date . '_' . date('YmdHis'), function($file) use($dataSheet1, $dataSheet2, $dataSheet3, $dataSheet4, $end_date){

            // Our first sheet
            $file->sheet('Summary Komisi', function($sheet) use($dataSheet1)
            {

            });

            // Our second sheet
            $file->sheet('Format Komisi Final', function($sheet) use($dataSheet2) {

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

            });

        })->export('xls');

        return redirect()->back();

    }

    private function getSheetData1($data)
    {

    }

    private function getSheetData2($data)
    {

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

    private function getSheetData4($data)
    {

    }
}

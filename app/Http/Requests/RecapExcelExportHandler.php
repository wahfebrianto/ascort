<?php
namespace App\Http\Requests;
use Maatwebsite\Excel\Files\ExportHandler;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Input;
use App\Agent;
class RecapExcelExportHandler implements ExportHandler
{

    public function handle($file)
    {
        $ctr = 1;
        // change all data that need to be formatted
        $dataSheet1 = $this->getSheetData1($file->data);
        $dataSheet3 = $this->getSheetData3($file->data);
        $dataSheet2 = $this->getSheetData2($dataSheet3);
        $dataSheet4 = $this->getSheetData4();
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');

        Excel::create('recap_' . $start_date . '_' . $end_date . '_' . date('YmdHis'), function($file) use($dataSheet1, $dataSheet2, $dataSheet3, $dataSheet4){

            // Our first sheet
            $file->sheet('Summary Komisi', function($sheet) use($dataSheet1)
            {

            });

            // Our second sheet
            $file->sheet('Format Komisi Final', function($sheet) use($dataSheet2) {

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
            $file->sheet('Rekap Mingguan Investor Baru', function($sheet) use($dataSheet3){
              $columnCount = count($dataSheet3[0]);
              $sheet->cells('1', function($cells)
              {
                  $cells->setFontSize(12);
                  $cells->setFontWeight('bold');
              });
              $sheet->setBorder('A1:' . chr(64 + $columnCount) . strval(sizeof($dataSheet3) + 1), 'solid');
              $sheet->setFontSize(12);
              $sheet->fromArray($dataSheet3, null, 'A1', true, true);
              $sheet->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
              $sheet->cells('A1:' . chr(64 + $columnCount) . '1', function($cells)
              {
                  $cells->setBackground('#AAAAAA');
                  $cells->setAlignment('center');
              });
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
      $result = [];
      $ctr = 1;
      foreach ($data as $agent) {
        foreach ($agent->sales as $sale) {
          $rowData = [];
          $rowData["No"] = $ctr;
          $rowData["Nama Investor"] = $sale->customer_name;
          $rowData["NPWP"] = ($sale->customer->NPWP == 0)?'NA':$sale->customer->NPWP;
          $rowData["Alamat"] = $sale->customer->address . ', ' . $sale->customer->city;
          $rowData["Email Address"] = ($sale->customer->email == '')?'NA':$sale->customer->email;
          $rowData["Dana Penempatan (Rp)"] = \App\Money::format('%(.2n', $sale->nominal);
          $rowData["Jk Waktu Investasi"] = $sale->MGI;
          $rowData["Nama Agent"] = $agent->name;
          $rowData["Agent Code"] = $agent->agent_code;
          $rowData["Nama Leader"] = ($agent->parent == NULL)?'NA':$agent->parent->name;
          $rowData["Leader Code"] = ($agent->parent == NULL)?'NA':$agent->parent->agent_code;
          $rowData["Bilyet dikirim ke:"] = '';
          $rowData["Tgl Transfer"] = Carbon::createFromFormat('d/m/Y', $sale->MGI_start_date)->format('d-M-y');
          $result[] = $rowData;
          $ctr++;
        }
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

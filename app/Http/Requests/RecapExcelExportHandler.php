<?php
namespace App\Http\Requests;
use Maatwebsite\Excel\Files\ExportHandler;

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

        // work on the exportAgentsExcelExport
        return Excel::create('RekapKomisiMingguan', function($file) {

            // Our first sheet
            $file->sheet('Summary Komisi', function($sheet) use ($file)
            {
                $columnCount = count($file->data[0]);
                $sheet->cells('1', function($cells)
                {
                    $cells->setFontSize(12);
                    $cells->setFontWeight('bold');
                });
                $sheet->setBorder('A1:' . chr(64 + $columnCount) . strval(sizeof($file->data) + 1), 'solid');
                $sheet->setFontSize(12);
                if (config('global.export_type') == 'pdf') {
                    $sheet->setOrientation('landscape');
                }
                $sheet->fromArray($file->data, null, 'A1', true, true);
                $sheet->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
                $sheet->cells('A1:' . chr(64 + $columnCount) . '1', function($cells)
                {
                    //$cells->setFontSize(8);
                    $cells->setBackground('#AAAAAA');
                    $cells->setAlignment('center');
                });
            });

            // Our second sheet
            $excel->sheet('Second sheet', function($sheet) {

            });

        })->export('xls');
        return $file->sheet('agents', function($sheet) use ($file)
        {
            $columnCount = count($file->data[0]);
            $sheet->cells('1', function($cells)
            {
                $cells->setFontSize(12);
                $cells->setFontWeight('bold');
            });
            $sheet->setBorder('A1:' . chr(64 + $columnCount) . strval(sizeof($file->data) + 1), 'solid');
            $sheet->setFontSize(12);
            if (config('global.export_type') == 'pdf') {
                $sheet->setOrientation('landscape');
            }
            $sheet->fromArray($file->data, null, 'A1', true, true);
            $sheet->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A3);
            $sheet->cells('A1:' . chr(64 + $columnCount) . '1', function($cells)
            {
                //$cells->setFontSize(8);
                $cells->setBackground('#AAAAAA');
                $cells->setAlignment('center');
            });
        })->export(config('global.export_type'));
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
          $rowData["Nama Investor"] = $datum->sales;
          $rowData["no"] = $ctr;
          $result[] = $rowData;
          $ctr++;
        }
      }
      return $result;
    }

    private function getSheetData4($data)
    {

    }
}

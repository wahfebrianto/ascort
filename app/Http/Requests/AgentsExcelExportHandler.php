<?php
namespace App\Http\Requests;
use Maatwebsite\Excel\Files\ExportHandler;

class AgentsExcelExportHandler implements ExportHandler
{

    public function handle($file)
    {
        $ctr = 1;
        // change all data that need to be formatted
        foreach ($file->data as &$datum) {

            foreach ($datum as $key => &$value) {
                //dd($datum);
                if ($key == 'agent_position_id') {
                    $value                   = $datum['agent_position']['name'];
                    // change key
                    $datum['agent_position'] = $value;
                    unset($datum[$key]);
                } elseif ($key == 'gender') {
                    $value = trans('general.gender.' . $value);
                } elseif ($key == 'DOB') {
                    //$value = \DateTime::createFromFormat('Y-m-d', $value)->format('m/d/Y'); // excel format
                    $value = date_format(date_create($value), 'm/d/Y');
                } elseif ($key == 'id_card_expiry_date') {
                    //$value = \DateTime::createFromFormat('Y-m-d', $value)->format('m/d/Y'); // excel format
                    $value = date_format(date_create($value), 'm/d/Y');
                } else if ($key == 'branch_office_id') {
                    $value = \App\BranchOffice::getBranchOfficeFromId($datum['branch_office_id'])->branch_name;
                } else if ($key == 'parent_id') {
                    $value = $datum['parent_name'];
                }
            }
            unset($datum["parent_name"]);
            //unset($datum["agent_position_name"]);
            $columnLang = trans('agents/general.columns');
            $newdatum   = array();
            foreach ($datum as $key => $value) {
                $newdatum[$columnLang[$key]] = $datum[$key];
            }
            $datum = array(
                'No' => $ctr++
            );
            $datum = array_merge($datum, $newdatum);

        }
        // work on the exportAgentsExcelExport
        return Excel::create('RekapKomisiMingguan', function($file) {

            // Our first sheet
            $file->sheet('agents', function($sheet) use ($file)
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

}

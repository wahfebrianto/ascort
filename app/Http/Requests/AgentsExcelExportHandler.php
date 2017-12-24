<?php

use Maatwebsite\Excel\Files\ExportHandler;

class AgentsExcelExportHandler implements ExportHandler {

    public function handle($file)
    {
        // change all data that need to be formatted
        foreach($file->data as &$datum) {
            foreach ($datum as $key => &$value) {
                if ($key == 'agent_position_id') {
                    $value = $datum['agent_position']['name'];
                    // change key
                    $datum['agent_position'] = $value;
                    unset($datum[$key]);
                } elseif($key == 'gender') {
                    $value = trans('general.gender.' . $value);
                } elseif($key == 'DOB') {
                    $value = \DateTime::createFromFormat('Y-m-d', $value)->format('m/d/Y'); // excel format
                } elseif($key == 'id_card_expiry_date') {
                    $value = \DateTime::createFromFormat('Y-m-d', $value)->format('m/d/Y'); // excel format
                }
            }
        }
        // work on the exportAgentsExcelExport
        return $file->sheet('agents', function($sheet) use($file)
        {
            $sheet->cells('1', function($cells) {
                $cells->setFontSize(12);
                $cells->setFontWeight('bold');
            });
            $sheet->fromArray($file->data, null, 'A1', true, true);
        })->export('xlsx');
    }

}
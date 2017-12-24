<?php

namespace App\Http\Requests;

use Maatwebsite\Excel\Files\ExportHandler;

class CustomersExcelExportHandler implements ExportHandler {

    public function handle($file)
    {
        // change all data that need to be formatted
        foreach($file->data as &$datum) {
            foreach ($datum as $key => &$value) {
                if($key == 'gender') {
                    $value = trans('general.gender.' . $value);
                } elseif($key == 'DOB') {
                    $value = \DateTime::createFromFormat('Y-m-d', $value)->format('m/d/Y'); // excel format
                } elseif($key == 'id_card_expiry_date') {
                    $value = \DateTime::createFromFormat('Y-m-d', $value)->format('m/d/Y'); // excel format
                }
            }
        }
        // work on the exportAgentsExcelExport
        return $file->sheet('customers', function($sheet) use($file)
        {
            $sheet->cells('1', function($cells) {
                $cells->setFontSize(12);
                $cells->setFontWeight('bold');
            });
            $sheet->fromArray($file->data, null, 'A1', true, true);
        })->export('xlsx');
    }

}

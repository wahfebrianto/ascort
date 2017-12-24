<?php
namespace App\Http\Requests;

use Maatwebsite\Excel\Files\ExportHandler;
use Carbon\Carbon;

class TaxExcelExportHandler implements ExportHandler {

    public function handle($file)
    {
        // change all data that need to be formatted
        foreach($file->data as &$datum) {
            foreach ($datum as $key => &$value) {
                if ($key == 'agent_id') {
                    $value = $datum['agent']['name'];
                    // change key
                    $datum['agent_name'] = $value;
                    $datum['agent_position_name'] = \App\AgentPosition::where('id', '=', $datum['agent']['agent_position_id'])->first()->name;
                    unset($datum[$key]);
                } else if($key == 'process_date') {
                    $value = Carbon::parse($value)->format('m/d/Y'); // excel format
                } else if(in_array($key, ['total_nominal', 'total_FYP', 'minus', 'cuts', 'commission_hold', 'created_at', 'updated_at', 'data'])) {
                    unset($datum[$key]);
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
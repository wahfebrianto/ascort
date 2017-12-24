<?php

namespace App\Http\Requests;

use Maatwebsite\Excel\Files\NewExcelFile;

class TaxExcelExport extends NewExcelFile {

    /**
     * @var array
     */
    public $data;

    public function getFilename()
    {
        $period = \Input::get('period');
        $month = \Input::get('month');
        $year = \Input::get('year');

        return 'tax_' . \App\CommissionReport::generatePeriodCode($period, $month, $year) . '_' . date('YmdHis');
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}

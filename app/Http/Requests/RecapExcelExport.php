<?php

namespace App\Http\Requests;

use Maatwebsite\Excel\Files\NewExcelFile;

class RecapExcelExport extends NewExcelFile {

    /**
     * @var array
     */
    public $data;

    public function getFilename()
    {
        $start_date = Input::get('start_date');
        $end_date = Input::get('end_date');

        return 'recap_' . $start_date . '_' . $end_date . '_' . date('YmdHis');
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}

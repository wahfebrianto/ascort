<?php

namespace App\Http\Requests;

use Maatwebsite\Excel\Files\NewExcelFile;

class SalesExcelExport extends NewExcelFile
{
    /**
     * @var array
     */
    public $data;

    public function getFilename()
    {
        return 'sales_list' . date('YmdHis');
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}

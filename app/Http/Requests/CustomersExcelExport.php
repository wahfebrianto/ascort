<?php

namespace App\Http\Requests;

use Maatwebsite\Excel\Files\NewExcelFile;

class CustomersExcelExport extends NewExcelFile
{
    /**
     * @var array
     */
    public $data;

    public function getFilename()
    {
        return 'customers_list' . date('YmdHis');
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}

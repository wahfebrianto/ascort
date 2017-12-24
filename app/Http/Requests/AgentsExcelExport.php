<?php

namespace App\Http\Requests;

use Maatwebsite\Excel\Files\NewExcelFile;

class AgentsExcelExport extends NewExcelFile {

    /**
     * @var array
     */
    public $data;

    public function getFilename()
    {
        return 'agents_list' . date('YmdHis');
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}

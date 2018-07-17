<?php

namespace App\Http\Requests;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RecapExport implements WithMultipleSheets {

    /**
     * @var array
     */
    public $agents, $start_date, $end_date;

    public function __construct($agents, $start_date, $end_date)
    {
        $this->$agents = $agents;
        $this->$start_date = $start_date;
        $this->$end_date = $end_date;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new InvoicesPerMonthSheet($this->year, $month);
        return $sheets;
    }
}

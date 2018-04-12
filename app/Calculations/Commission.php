<?php
/**
 * Created by PhpStorm.
 * User: harto
 * Date: 3/16/2016
 * Time: 11:43 AM
 */

namespace App\Calculations;

use App\Agent;
use App\CommissionReport;
use Carbon\Carbon;
use App\Calculations\Base\Slips;

class Commission extends Slips
{
    const TYPE = "COMM";

    public function __construct(Agent $agent, $start_date, $end_date)
    {
        $this->agent = $agent;
        $this->start_date = Carbon::createFromFormat('d/m/Y H:i:s', $start_date.' 00:00:00');
        $this->end_date = Carbon::createFromFormat('d/m/Y H:i:s', $end_date.' 23:59:59');
        parent::__construct();
    }

    public function calculate($force_calculate = false)
    {
        if(!$force_calculate && $this->isSaved()) {
            //$this->rounding();
            $this->load();
        } else {
            $this->sales = $this->agent->sales()->isActive()->MgiDateBetween($this->start_date, $this->end_date)->get();
            $this->total_nominal = 0;
            $this->total_FYP = 0;
            $this->total_commission = 0;
            $this->additional = 0;
            foreach ($this->sales as $sale) {
                $this->total_nominal += $sale->nominal;
                $this->total_FYP += $sale->FYP;
                $this->total_commission += $sale->agentCommissionValue;
                $this->additional += $sale->additional;
            }

            $this->gross_commission = $this->total_commission - $this->commission_hold - $this->minus - $this->cuts + $this->additional;

            /*
            $this->tax = Tax::getTax($this->gross_commission);
            $this->tax *= 0.5; //discount 50%
            if ($this->agent->NPWP == "0") $this->tax *= 1.2; //if agent doesn't have NPWP, add 20%
            */
            $this->tax = 0;

            $this->data = [
                'agent' => $this->agent,
                'sales' => $this->sales
            ];

            $this->nett_commission = $this->gross_commission - $this->tax;
            //$this->rounding();
            // $this->save();
        }
    }

    public function loadData($data)
    {
        if(array_key_exists('agent', $data))
            $this->agent = $data->agent;
        if(array_key_exists('sales', $data))
            $this->sales = $data->sales;

        foreach($this->sales as &$sale) {
            $sale->MGI_start_date = Carbon::parse($sale->MGI_start_date)->format('d/m/Y');
        }
    }

    public static function getCommission($agent, $period, $month, $year, $force_recalculation = false)
    {
        $comm = new Commission($agent, $period, $month, $year);
        $comm->calculate($force_recalculation);
        return $comm;
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: harto
 * Date: 3/16/2016
 * Time: 12:51 PM
 */

namespace App\Calculations;

use App\Agent;
use App\CommissionReport;
use Carbon\Carbon;
use App\Calculations\Base\Slips;
use App\OverridingCommissionPercentage;
use App\AgentPosition;

class TopOverriding extends Slips
{
    const TYPE = "RECFEE";
    public $sales_owner;
    public $sales_owner_level;
    public $sales_ovr_percentage;
    public $sales_ovr_value;
    public $sales_inline_data;

    public function __construct(Agent $agent, $start_date, $end_date)
    {
        $this->agent = $agent;
        $this->start_date = Carbon::createFromFormat('d/m/Y H:i:s',$start_date.' 00:00:00');
        $this->end_date = Carbon::createFromFormat('d/m/Y H:i:s',$end_date.' 23:59:59');

        $this->sales_inline_data = [];
        $this->sales_owner = [];
        $this->sales_owner_level = [];
        $this->sales_ovr_percentage = [];
        $this->sales_ovr_value = [];
        parent::__construct();
    }

    /**
     * Indirect recursive call to get all sales of all children.
     * @return array
     */
    public function getTopOverridingSales()
    {
        $highest_agent_position = AgentPosition::getHighestAgentPosition();
        $this->sales_inline_data = [];
        $rec_agents = $this->agent->getTopOverrideAgents();
        foreach($rec_agents as $level => $rec_agent) {
            foreach($rec_agent as $agent) {
                $this->getTopOverridingSalesRec($agent, $agent, $this->sales_inline_data, $level);
            }
        }

        for($i = count($this->sales_inline_data)-1; $i >= 0; $i--) {
            // calculate OVR commission
            $this->sales_inline_data[$i]['ovr_percentage'] = OverridingCommissionPercentage::getTopOverridingCommissionPercentageOfLevel($this->sales_inline_data[$i]['level']);
            $this->sales_inline_data[$i]['ovr'] = [];
            foreach ($this->sales_inline_data[$i]['sales'] as $sale) {
                $this->sales_inline_data[$i]['ovr'][$sale->id] = $sale->FYP * $this->sales_inline_data[$i]['ovr_percentage'] / 100;
            }
            /*}*/
        }

        $this->total_nominal = 0;
        $this->total_FYP = 0;
        $this->total_commission = 0;
        $ctr = 0;
        foreach ($this->sales_inline_data as $sale) {
            foreach ($sale['sales'] as $sal) {
                $this->sales[$ctr] = $sal;
                $this->sales_owner[$ctr] = $sale['owner'];
                $this->sales_owner_level[$ctr] = $sale['level'];
                $this->sales_ovr_percentage[$ctr] = $sale['ovr_percentage'];
                $this->sales_ovr_value[$ctr] = $sale['ovr'][$sal->id];

                $this->total_nominal += $sal->nominal;
                $this->total_FYP += $sal->FYP;
                $this->total_commission += $sale['ovr'][$sal->id];
                $ctr++;
            }
        }
    }

    /**
     * Recursive call to get all active sales of a period of all children. Note than self sales also added.
     * @param \App\Agent $agent
     * @param \App\Agent $upper_agent
     * @param array $arr
     * @param int $level
     */
    private function getTopOverridingSalesRec(Agent $agent, Agent $upper_agent, Array &$arr, $level = 0)
    {
        if (count($agent->childrenRecursive) == 0) {
            $sales = $agent->sales()->isActive()->ofPeriodDateBetween($this->start_date,$this->end_date)->get();
            if(count($sales) != 0) {
                $temp = [
                    'sales' => $sales,
                    'owner' => $upper_agent,
                    'level' => $level,
                ];
                $arr[] = $temp;
            }
            return;
        } else {
            $sales = $agent->sales()->isActive()->ofPeriodDateBetween($this->start_date,$this->end_date)->get();
            if(count($sales) != 0) {
                $temp = [
                    'sales' => $sales,
                    'owner' => $upper_agent,
                    'level' => $level,
                ];
                $arr[] = $temp;
            }
            foreach ($agent->childrenRecursive as $child) {
                if($child->agent_position_id != \App\AgentPosition::getHighestAgentPosition()->id)
                    $this->getTopOverridingSalesRec($child, $upper_agent, $arr, $level);
            }
            return;
        }
    }

    public function calculate($force_calculate = true)
    {
        if($this->isSaved() && !$force_calculate) {
            //$this->rounding();
            $this->load();
        } else {
            $this->getTopOverridingSales();

            $this->gross_commission = $this->total_commission - $this->commission_hold - $this->minus - $this->cuts;

            /*
            $this->tax = Tax::getTax($this->gross_commission);
            $this->tax *= 0.5; //discount 50%
            if ($this->agent->NPWP == "0") $this->tax *= 1.2; //if agent doesn't have NPWP, add 20%
            */
            $this->tax = 0;

            $this->data = [
                'agent' => $this->agent,
                'sales' => $this->sales,
                'sales_owner' => $this->sales_owner,
                'sales_owner_level' => $this->sales_owner_level,
                'sales_ovr_value' => $this->sales_ovr_value,
                'sales_ovr_percentage' => $this->sales_ovr_percentage,
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
        if(array_key_exists('sales_owner', $data))
            $this->sales_owner = $data->sales_owner;
        if(array_key_exists('sales_ovr_value', $data))
            $this->sales_ovr_value = $data->sales_ovr_value;
        if(array_key_exists('sales_ovr_percentage', $data))
            $this->sales_ovr_percentage = $data->sales_ovr_percentage;
        if(array_key_exists('sales_owner_level', $data))
            $this->sales_owner_level = $data->sales_owner_level;

        foreach($this->sales as &$sale) {
            $sale->MGI_start_date = Carbon::parse($sale->MGI_start_date)->format('d/m/Y');
        }
    }

    public static function getTopOverriding($agent, $period, $month, $year, $force_recalculation = false)
    {
        $obj = new TopOverriding($agent, $period, $month, $year);
        $obj->calculate($force_recalculation);
        return $obj;
    }
}

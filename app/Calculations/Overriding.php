<?php
/**
 * Created by PhpStorm.
 * User: harto
 * Date: 3/16/2016
 * Time: 12:51 PM
 */

namespace App\Calculations;

use App\Agent;
use App\AgentPosition;
use App\Calculations\Base\Slips;
use App\CommissionReport;
use App\OverridingCommissionPercentage;
use Carbon\Carbon;
use Log;

class Overriding extends Slips
{
    const TYPE = "OVR";
    public $sales_owner;
    public $sales_ovr_percentage;
    public $sales_ovr_value;
    public $sales_inline_data;

    public function __construct(Agent $agent, $period, $month, $year)
    {
        $this->agent = $agent;
        $this->period = $period;
        $this->month = $month;
        $this->year = $year;

        $this->sales_owner = [];
        $this->sales_ovr_percentage = [];
        $this->sales_ovr_value = [];
        parent::__construct();
    }

    /**
     * Indirect recursive call to get all sales of all children.
     * @return array
     */
    public function getOverridingSales()
    {
        $highest_agent_position = AgentPosition::getHighestAgentPosition();
        /*
         * Get all child's sales, do some filter, calc commission from percentage from self's overriding percentage
         */
        $this->sales_inline_data = [];
        foreach($this->agent->childrenRecursive as $child) {
            $this->getOverridingSalesRec($child, $child, $this->sales_inline_data, 0);
        }
        for($i = count($this->sales_inline_data)-1; $i >= 0; $i--) {
            // remove if self sales, and if child is BD
            if ($this->sales_inline_data[$i]['owner']->id == $this->agent->id) {
                array_splice($this->sales_inline_data, $i, 1);
            } elseif($this->sales_inline_data[$i]['owner']->agent_position_id == $highest_agent_position->id) { // remove if highest agent position
                array_splice($this->sales_inline_data, $i, 1);
            } elseif($this->sales_inline_data[$i]['owner']->agent_position_id < $this->agent->agent_position_id) { // remove if sales owner position_id > than processed agent_position_id
                array_splice($this->sales_inline_data, $i, 1);
            } else {
                // calculate OVR commission
                $percentages = $this->agent->getOverridingPercentage($this->sales_inline_data[$i]['owner']->agent_position_id);
                foreach ($percentages as $percentage) {
                    $this->sales_inline_data[] = $this->sales_inline_data[$i];

                    $last_index = count($this->sales_inline_data) - 1;
                    $this->sales_inline_data[$last_index]['ovr_percentage'] = $percentage;
                    foreach ($this->sales_inline_data[$last_index]['sales'] as $sale) {
                        $this->sales_inline_data[$last_index]['ovr'][$sale->id] = $this->agent->getOverridingValue($sale, $percentage); // overriding value of sale id
                    }
                }
                array_splice($this->sales_inline_data, $i, 1);
            }
        }

        /*
         * Get all myself sales, percentage from my position
         */
        $agent_positions = $this->agent->getOverrideAgentPositions();
        $my_sales = $this->agent->sales()->isActive()->ofPeriod($this->period, $this->month, $this->year)->get();
        foreach($agent_positions as $agent_position) {
            $temp = [
                'sales' => $my_sales,
                'owner' => $this->agent,
                'level' => 0
            ];
            $this->sales_inline_data[] = $temp;

            $i = count($this->sales_inline_data) - 1;
            $this->sales_inline_data[$i]['ovr_percentage'] = OverridingCommissionPercentage::getOverridingCommissionPercentageOf($agent_position->id);
            foreach ($this->sales_inline_data[$i]['sales'] as $sale) {
                $this->sales_inline_data[$i]['ovr'][$sale->id] = $sale->FYP * $this->sales_inline_data[$i]['ovr_percentage'] / 100; // overriding value of sale id
            }
        }

        $this->total_nominal = 0;
        $this->total_FYP = 0;
        $this->total_commission = 0;
        $ctr = 0;
        foreach ($this->sales_inline_data as $sale) {
            foreach ($sale['sales'] as $sal) {
                $this->sales[$ctr] = $sal;
                $this->sales_owner[$ctr] = $sale['owner'];
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
    private function getOverridingSalesRec(Agent $agent, Agent $upper_agent, Array &$arr, $level = 0)
    {
        if (count($agent->childrenRecursive) == 0) {
            $sales = $agent->sales()->isActive()->ofPeriod($this->period, $this->month, $this->year)->get();
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
            $sales = $agent->sales()->isActive()->ofPeriod($this->period, $this->month, $this->year)->get();
            if(count($sales) != 0) {
                $temp = [
                    'sales' => $sales,
                    'owner' => $upper_agent,
                    'level' => $level,
                ];
                $arr[] = $temp;
            }
            foreach ($agent->childrenRecursive as $child) {
                $this->getOverridingSalesRec($child, $upper_agent, $arr, $level + 1);
            }
            return;
        }
    }

    public function calculate($force_calculate = false)
    {
        if($this->isSaved() && !$force_calculate) {
            //$this->rounding();
            $this->load();
        } else {
            $this->getOverridingSales();

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
                'sales_ovr_value' => $this->sales_ovr_value,
                'sales_ovr_percentage' => $this->sales_ovr_percentage,
            ];

            $this->nett_commission = $this->gross_commission - $this->tax;
            //$this->rounding();
            $this->save();
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

        foreach($this->sales as &$sale) {
            $sale->MGI_start_date = Carbon::parse($sale->MGI_start_date)->format('d/m/Y');
        }
    }

    public static function getOverriding($agent, $period, $month, $year, $force_recalculation = false)
    {
        $obj = new Overriding($agent, $period, $month, $year);
        $obj->calculate($force_recalculation);
        return $obj;
    }
}
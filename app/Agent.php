<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Nayjest\Grids\EloquentDataProvider;

class Agent extends Model
{
    protected $table = "agents";
        protected $fillable = ['agent_code', 'NIK', 'name', 'birth_place', 'DOB', 'gender', 'address', 'state', 'city',
        'zipcode', 'phone_number', 'handphone_number', 'email', 'agent_position_id', 'join_date',
        'NPWP', 'bank', 'bank_branch', 'account_name', 'account_number', 'branch_office_id', 'parent_id'];
    protected $dates = ['DOB'];
    protected $appends = ['parent_name', 'agent_position_name'];
    protected $hidden = ['childrenRecursive', 'sales'];

    // ---------------------------------------------- RELATIONSHIP --------------------------------------------------

    public function agent_position()
    {
        return $this->belongsTo('App\AgentPosition', 'agent_position_id');
    }

    public function sales()
    {
        return $this->hasMany('App\Sale', 'agent_id');
    }

    public function commissionReports()
    {
        return $this->hasMany('App\CommissionReport', 'agent_id');
    }

    public function taxReports()
    {
        return $this->hasMany('App\TaxReport', 'agent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Agent', 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Agent', 'parent_id');
    }

    public function history()
    {
        return $this->hasMany('App\AgentPositionHistory', 'agent_id');
    }

    public function branchOffice()
    {
        return $this->belongsTo('App\BranchOffice', 'branch_office_id');
    }

    // ------------------------------------------ END OF RELATIONSHIP -----------------------------------------------

    // ----------------------------------------------- ATTRIBUTE ----------------------------------------------------

    public function setDOBAttribute($date){
        $this->attributes['DOB'] = Carbon::createFromFormat('d/m/Y', $date);
    }

    public function getDOBAttribute($date){
        return Carbon::parse($date)->format('d/m/Y');
    }

    public function setJoinDateAttribute($date){
        $this->attributes['join_date'] = Carbon::createFromFormat('d/m/Y', $date);
    }

    public function getJoinDateAttribute($date){
        return Carbon::parse($date)->format('d/m/Y');
    }

    public function getMyTotalFYPAttribute(){
        $builder = DB::table('sales')
            ->where('agent_id', '=', $this->id);

        if(Carbon::now() >= Carbon::create(Carbon::now()->year-1, 12, 31)->addWeeks(3) && Carbon::now() <= Carbon::create(Carbon::now()->year, 6, 30)->addWeeks(3)){
            $dayOfYear = Carbon::create(Carbon::now()->year-1, 12, 31)->addWeeks(3)->dayOfYear;
            $builder = $builder->whereRaw("created_at between MAKEDATE(YEAR(now()), " . $dayOfYear .") and now()");
        }else{
            //second half of the year
            $dayOfYear = Carbon::create(Carbon::now()->year, 7, 1)->addWeeks(3)->dayOfYear;
            $builder = $builder->whereRaw("created_at between MAKEDATE(YEAR(now()), " . $dayOfYear .") and now()");
        }

        return $builder
            ->select(DB::raw('sum(nominal * (MGI_month / 12)) AS total_FYP'))
            ->first()->total_FYP;
    }

    public function getMyTotalNominalAttribute(){
        $builder = DB::table('sales')
            ->where('agent_id', '=', $this->id);

        if(Carbon::now() >= Carbon::create(Carbon::now()->year-1, 12, 31)->addWeeks(3) && Carbon::now() <= Carbon::create(Carbon::now()->year, 6, 30)->addWeeks(3)){
            $dayOfYear = Carbon::create(Carbon::now()->year-1, 12, 31)->addWeeks(3)->dayOfYear;
            $builder = $builder->whereRaw("created_at between MAKEDATE(YEAR(now()), " . $dayOfYear .") and now()");
        }else{
            //second half of the year
            $dayOfYear = Carbon::create(Carbon::now()->year, 7, 1)->addWeeks(3)->dayOfYear;
            $builder = $builder->whereRaw("created_at between MAKEDATE(YEAR(now()), " . $dayOfYear .") and now()");
        }

        return $builder
            ->select(DB::raw('sum(nominal) AS total_nominal'))
            ->first()->total_nominal;
    }

    public function getTotalFYPAttribute()
    {
        $sum = $this->my_total_FYP;
        foreach ($this->children as $item) {
            $sum += $item->total_FYP;
        }
        return $sum;
    }

    public function getTotalNominalAttribute()
    {
        $sum = $this->my_total_nominal;
        foreach ($this->children as $item) {
            $sum += $item->total_nominal;
        }
        return $sum;
    }

    public function getSalesAttribute()
    {
        return $this->sales()->where('is_active', '=', 1)->get();
    }

    public function getAgentPositionNameAttribute()
    {
        return $this->agent_position()->first()['name'];
    }

    public function getAgentPositionAcronymAttribute()
    {
        return $this->agent_position()->first()['acronym'];
    }

    public function getAgentPositionLevelAttribute()
    {
        return $this->agent_position()->first()['level'];
    }

    public function getBranchOfficeNameAttribute()
    {
        return $this->branchOffice()->first()['name'];
    }

    public function getParentNameAttribute()
    {
        $parent = empty($this->parent()->first()['name']) ? '-' : $this->parent()->first()['name'];
        return $parent;
    }

    // -------------------------------------------- END OF ATTRIBUTE ------------------------------------------------


    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function scopeSuperParent($query) {
        $query->where('parent_id', '=', null);
    }

    public function scopeIsActive($query) {
        $query->where('is_active', '=', '1');
    }

    public function getOverridingPercentage($child_agent_position_id, $level = 0)
    {
        $percentage = \App\OverridingCommissionPercentage
            ::where('agent_position_id', '=', $this->agent_position_id)
            ->where('override_from', '=', null)
            ->where('level', '=', $level)
            ->first();

        // get all overriding percentages for the parent
        $satisfied = false;
        $percentages = [];
        while (!$satisfied) {
            if($percentage != null && $percentage->agent_position_id != $child_agent_position_id) {
                $percentages[] = $percentage->percentage;
                $percentage = $percentage->children;
            } else {
                $satisfied = true;
            }
        }
        return count($percentages) == 0 ? [0] : $percentages;
    }

    public function getOverridingValue(\App\Sale $sale, $ovr_percentage)
    {
        return ($ovr_percentage / 100) * $sale->FYP;
    }


    // ----------------------------------------------- GET AGENT ----------------------------------------------------
    public static function getAgentFromId($id)
    {
        $found = Agent::where('id', '=', $id)->where('is_active', '=', 1)->get();
        return (count($found)>0 ? $found[0] : null);
    }

    public static function getAgents()
    {
        return Agent::where('is_active', '=', 1)->get();
    }

    public static function getAgentsWithSales()
    {
        return Agent::with('sales')->with('agent_position')->where('is_active', '=', 1)->get();
    }

    public static function getAgents_ForDropDown()
    {
        $agents = Agent::getAgents();
        $data = [];
        foreach($agents as $agent){
            $data[$agent->id] = $agent->name . " [" . $agent->AgentPositionName . "]";
        }
        return $data;
    }

    public static function getAgentsWithPositionName_ForDropDown()
    {
        $agents = Agent::with('agent_position')->whereIn('branch_office_id', \App\BranchOffice::getBranchOfficesID())->get();
        $arr = [];
        foreach ($agents as $agent) {
            $arr[$agent->id] = $agent->name . ' (' . $agent->agentPositionAcronym . ')';
        }
        return $arr;
    }

    // ------------------------------------------- END OF GET AGENT --------------------------------------------------


    public static function getColumnsArray()
    {
        return (new Agent())->getFillable();
    }

    public static function getIndexDataProvider($enabledOnly = 1)
    {
        // grids filter and sorting workaround -> https://github.com/Nayjest/Grids/issues/41
        return new EloquentDataProvider(\App\Agent
            ::join('agent_positions', 'agent_positions.id', '=', 'agents.agent_position_id')
            ->leftJoin('agents AS a', 'agents.parent_id', '=', 'a.id')
            ->select('agents.*')
            ->addSelect('agent_positions.name as apname')
            ->addSelect('a.name as aname')
            ->where('agents.is_active', $enabledOnly)
            ->whereIn('agents.branch_office_id', \App\BranchOffice::getBranchOfficesID()));
    }


    public static function updateAgentCode($agent){
        $agent->update(['agent_code' => str_pad((string) $agent->id, 6, '0', STR_PAD_LEFT)]);
    }

    public static function changeParentFromDisable(Agent $disabledAgent){
        foreach($disabledAgent->children as $child){
            if($disabledAgent->parent == null){
                $child->parent_id = null;
            }else{
                $child->parent_id = $disabledAgent->parent->id;
            }
        }
    }

    public static function evaluateAgents(){
        $agents = Agent::getAgents();
        $data = [];
        foreach($agents as $agent){
            if($agent->TotalFYP != null && (int)$agent->TotalFYP < SalesTarget::getTargetAmountFromAgentPosition($agent->agent_position_id))
                $data[] = $agent;
        }
        return $data;
    }

    public function getOverrideAgentPositions()
    {
        $level = $this->agent_position->level;
        $lowest = AgentPosition::getLowestAgentPosition();
        return AgentPosition
            ::where('level', '>=', $level)
            ->where('id', '<>', $lowest->id)
            ->where('is_active', '=', 1)
            ->get();
    }

    public function getTopOverrideAgents() {
        // 3 levels
        if ($this->agent_position_id != AgentPosition::getHighestAgentPosition()->id) return [];
        $agents = [1=> [],2 => [],3 => []];
        $this->getTopOverrideAgentsRec($agents, 1);
        return $agents;
    }

    public function getTopOverrideAgentsRec(Array &$arr, $level = 1) {
        if($level == 4) return;
        foreach ($this->childrenRecursive as $child) {
            if($child->agent_position_id == $this->agent_position_id) {
                $arr[$level][] = $child;
                $child->getTopOverrideAgentsRec($arr, $level + 1);
            }
        }
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nayjest\Grids\EloquentDataProvider;

class AgentPositionHistory extends Model
{
    protected  $table = "agent_position_histories";
    protected $fillable = ['agent_id', 'agent_position_id'];
    //----------------------------------------------- RELATIONSHIP -------------------------------------------------

    public function agent(){
        return $this->belongsTo('App\Agent', 'agent_id');
    }

    public function agent_position()
    {
        return $this->belongsTo('App\AgentPosition', 'agent_position_id');
    }
    //--------------------------------------------------------------------------------------------------------------

    public static function getAgentHistory($agentID){
        return AgentPositionHistory::where('agent_id', '=', $agentID)->get();
    }

    public static function getIndexDataProvider($enabledOnly = 1)
    {
        // grids filter and sorting workaround -> https://github.com/Nayjest/Grids/issues/41
        return new EloquentDataProvider(self
            ::join('agents', 'agent_position_histories.agent_id', '=', 'agents.id')
            ->join('agent_positions', 'agent_positions.id', '=', 'agents.agent_position_id')
            ->select('agents.*')
            ->addSelect('agent_positions.name as apname')
            ->addSelect('agent_position_histories.*')
            ->where('agents.is_active', 1)
            ->whereIn('agents.branch_office_id', \App\BranchOffice::getBranchOfficesID()));
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OverridingCommissionPercentage extends Model
{
    protected $table = "overriding_commission_percentages";
    protected $fillable = ['agent_position_id', 'override_from', 'level', 'percentage'];

    // ---------------------------------------------- RELATIONSHIP --------------------------------------------------

    public function agent_position(){
        return $this->belongsTo('App\AgentPosition', 'agent_position_id');
    }

    public function override_from_agent(){
        return $this->belongsTo('App\AgentPosition', 'override_from');
    }

    // ------------------------------------------ END OF RELATIONSHIP -----------------------------------------------

    public function getChildrenAttribute() {
        if (count($this->agent_position->children) > 0) {
            return OverridingCommissionPercentage
                ::where('agent_position_id', '=', $this->agent_position->children[0]->id)
                ->where('override_from', '=', null)
                ->where('is_active', '=', 1)
                ->first();
        }
        return null;
    }

    public static function getOverridingCommissionPercentageFromId($id){
        $found = OverridingCommissionPercentage::where('id', '=', $id)->where('is_active', '=', 1)->get();
        return (count($found)>0 ? $found[0] : null);
    }

    public static function getOverridingCommissionPercentageOf($agent_position_id) {
        $found = OverridingCommissionPercentage
            ::where('agent_position_id', '=', $agent_position_id)
            ->where('override_from', '=', null)
            ->where('is_active', '=', 1)
            ->get();
        return (count($found)>0 ? $found[0]->percentage : 0);
    }
    public static function  getTopOverridingCommissionPercentageOfLevel($level = 1) {
        $agent_position_id = AgentPosition::getHighestAgentPosition()->id;
        $found = OverridingCommissionPercentage
            ::where('agent_position_id', '=', $agent_position_id)
            ->where('override_from', '=', $agent_position_id)
            ->where('level', '=', $level)
            ->get();
        return (count($found)>0 ? $found[0]->percentage : 0);
    }
}

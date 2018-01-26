<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentPosition extends Model
{
    protected $table = "agent_positions";
    protected $fillable = ['parent_id', 'name', 'acronym', 'level'];

    // ---------------------------------------------- RELATIONSHIP --------------------------------------------------

    public function children(){
        return $this->hasMany('App\AgentPosition', 'parent_id');
    }

    public function parent(){
        return $this->belongsTo('App\AgentPosition', 'parent_id');
    }

    public function agents(){
        return $this->hasMany('App\Agent', 'agent_position_id');
    }

    public function saleCommissionPercentage(){
        return $this->hasOne('App\SaleCommissionPercentage', 'agent_position_id');
    }

    public function overridingCommissionPercentage(){
        return $this->hasMany('App\OverridingCommissionPercentage', 'agent_position_id');
    }

    public function overrode_by(){
        return $this->hasMany('App\OverridingCommissionPercentage', 'override_from');
    }

    public function salesTarget(){
        return $this->hasOne('App\SalesTarget', 'agent_position_id');
    }

    public function history(){
        return $this->hasMany('App\AgentPositionHistory', 'agent_position_id');
    }

    // ------------------------------------------ END OF RELATIONSHIP -----------------------------------------------

    public function scopeWithoutLowest($query) {
        $lowest_id = self::getLowestAgentPosition()->id;
        return $query->where('id', '<>', $lowest_id)->where('is_active', '=', 1);
    }

    public function getChildrenAttribute(){
        return $this->children()->where('is_active', '=', 1)->get();
    }

    public function getAgentsAttribute(){
        return $this->agents()->where('is_active', '=', 1)->get();
    }

    public static function getAgentPositionFromId($id){
        $found = AgentPosition::where('id', '=', $id)->where('is_active', '=', 1)->get();
        return (count($found)>0 ? $found[0] : null);
    }

    public static function getAgentPositions(){
        return AgentPosition::where('is_active', '=', 1)->get();
    }

    public static function getAgentPositions_ForParentDropDown(){
        $parents = [];
        $parents["-"] = "None";
        $agentPos = AgentPosition::getAgentPositions();
        foreach($agentPos as $pos){
            $parents[$pos->id] = $pos->name;
        }
        return $parents;
    }

    public static function getAgentPositions_ForDropDown(){
        $parents = [];
        $agentPos = AgentPosition::getAgentPositions();
        foreach($agentPos as $pos){
            $parents[$pos->id] = $pos->name;
        }
        return $parents;
    }

    public static function getAgentPositions_ForDropDownCommission($commission){
        $parents = [];
        $agentPos = AgentPosition::getAgentPositions();
        foreach($agentPos as $pos){
            if($commission == "sale"){
                if($pos->saleCommissionPercentage == null){
                    $parents[$pos->id] = $pos->name;
                }
            }else{
				if($pos->id == $commission){
                $parents[$pos->id] = $pos->name;
				}
            }

        }
        return $parents;
    }

    public static function getHighestAgentPosition()
    {
        $min_level = self::where('is_active', '=', 1)->min('level');
        return self::where('level', '=', $min_level)->first();
    }

    public static function getLowestAgentPosition()
    {
        $max_level = self::where('is_active', '=', 1)->max('level');
        return self::where('level', '=', $max_level)->first();
    }
}

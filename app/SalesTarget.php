<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesTarget extends Model
{
    protected $table = "sales_targets";
    protected $fillable = ['agent_position_id', 'target_amount'];

    // ---------------------------------------------- RELATIONSHIP --------------------------------------------------

    public function agent_position(){
        return $this->belongsTo('App\AgentPosition', 'agent_position_id');
    }

    // ------------------------------------------ END OF RELATIONSHIP -----------------------------------------------


    public function getFormattedTargetAmountAttribute() {
        return \App\Money::format('%(.2n', $this->target_amount);
    }

    public function getAgentPositionNameAttribute()
    {
        return $this->agent_position()->getResults()['name'];
    }

    public static function getSalesTargetFromId($id)
    {
        return SalesTarget::findOrFail($id);
    }

    public static function getTargetAmountFromAgentPosition($positionID){
        return SalesTarget::where('agent_position_id', '=', $positionID)->first()->target_amount;
    }

}

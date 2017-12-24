<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleCommissionPercentage extends Model
{
    protected $table = "sale_commission_percentages";
    protected $fillable = ['agent_position_id', 'percentage'];

    // ---------------------------------------------- RELATIONSHIP --------------------------------------------------

    public function agent_position(){
        return $this->belongsTo('App\AgentPosition', 'agent_position_id');
    }

    // ------------------------------------------ END OF RELATIONSHIP -----------------------------------------------

    public static function getSaleCommissionPercentageFromId($id){
        $found = SaleCommissionPercentage::where('id', '=', $id)->where('is_active', '=', 1)->get();
        return (count($found)>0 ? $found[0] : null);
    }

}

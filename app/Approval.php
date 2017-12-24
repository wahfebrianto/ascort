<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Approval extends Model
{
    protected $table = "approvals";
    protected $fillable = ['user_id', 'subject', 'description'];

    // ---------------------------------------------- RELATIONSHIP --------------------------------------------------

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    // ------------------------------------------ END OF RELATIONSHIP -----------------------------------------------

    public function getDescriptionAttribute(){

        // $arr = json_decode($this->TrueDescription, true);
        //
        // if($this->subject == "Insert Sale Backdate"){
        //
        //     $attributes = json_decode($arr["attributes"], true);
        //     $customer = Customer::getCustomerFromId($attributes['customer_id']);
        //     $agent = Agent::getAgentFromId($attributes['agent_id']);
        //
        //     $str = "Insert sale with backdate <b>[" . $attributes['MGI_start_date'] . "]</b> for customer <b>" .
        //             $customer->name . '</b> with agent <b>' . $agent->name
        //             . '</b> in ' . $this->created_at;
        //
        // }else{
        //
        //     $theThing = (array) DB::table($arr['table'])->where('id', '=', $arr['id'])->first();
        //
        //     $str = "Update " . $arr['table'] . " with id #" . $arr['id'] . ", change: ";
        //     foreach($arr as $key => $value){
        //         if($key != "table" && $key != "id"){
        //             $str .= $key . " from " . $theThing[$key] . " to <b>" . $value . "</b>; ";
        //         }
        //     }
        //
        //     $str .= " for " . $this->subject . ".";
        // }
        //
        // return $str;
        return $this->attributes['description'];
    }

    public function getTrueDescriptionAttribute(){
        return $this->attributes['description'];
    }

    public static function getUnapproved(){
        return Approval::where('is_approved', '=', 0)->get();
    }

    public static function countUnapproved(){
        return count(Approval::where('is_approved', '=', 0)->get());
    }
}

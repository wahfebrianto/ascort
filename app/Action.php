<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected  $table = "actions";
    protected $fillable = ['action', 'execution_date'];
    protected $dates = ['execution_date'];
    //----------------------------------------------------------------------------------------------------------------

    public function setExecutionDateAttribute($date){
        $this->attributes['execution_date'] = Carbon::createFromFormat('d/m/Y', $date);
    }

    public function getExecutionDateAttribute($date){
        return Carbon::parse($date)->format('d/m/Y');
    }

    public static function getTodaysAction(){
        return Action::where('execution_date', '=', Carbon::now()->format('Y-m-d'))->get();
    }

}

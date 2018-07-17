<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class BoardOfDirector extends Model
{
    protected $table = "board_of_director";
    protected $fillable = ['bod_name', 'address', 'state', 'city', 'zipcode', 'phone_number','email','type','identity_number','NPWP','position','bank','bank_branch','account_number','account_name'];
    protected $appends = ['state_city'];

    public function getStateCityAttribute()
    {
        return "$this->city, $this->state";
    }


}

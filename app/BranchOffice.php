<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BranchOffice extends Model
{
    protected $table = "branch_offices";
    protected $fillable = ['branch_name', 'address', 'state', 'city', 'zipcode', 'phone_number'];
    protected $appends = ['state_city'];

    // ---------------------------------------------- RELATIONSHIP --------------------------------------------------

    public function customer(){
        return $this->hasMany('App\Customer', 'branch_office_id');
    }

    public function agent(){
        return $this->hasMany('App\Agent', 'branch_office_id');
    }

    public function sale(){
        return $this->hasMany('App\Sale', 'branch_office_id');
    }

    public function role(){
        return $this->hasMany('App\Models\Role', 'branch_office_id');
    }

    // ------------------------------------------ END OF RELATIONSHIP -----------------------------------------------

    public static function getBranchOffices(){
        return BranchOffice::where('is_active', '=', 1)->get();
    }
	public static function getBranchOfficeFromId($id){
        $found = BranchOffice::where('id', '=', $id)->where('is_active', '=', 1)->get();
        return (count($found)>0 ? $found[0] : null);
    }

    public function getStateCityAttribute()
    {
        return "$this->city, $this->state";
    }

    public static function getBranchOffices_forDropDown($nullable = false)
    {
      $branch_offices = [];
  	  $branchOfficeList = array();
  		foreach (Auth::user()->roles as $roles) {
  			if($roles->branch_office_id == null)
  			{
  				$branchOfficeList = \App\BranchOffice::where('is_active', '=', 1)->get();
  				break;
  			}
  			else if($roles->enabled == 1)
  			{
  				$branchOfficeList[] = \App\BranchOffice::find($roles->branch_office_id);
  			}
  		}
  		if($nullable)
  		{
  			$branch_offices[null] = "All";
  		}
      foreach ($branchOfficeList as $a) {
        $branch_offices[$a->id] = $a->branch_name;
      }
      return $branch_offices;
    }

    public static function getBranchOfficesID()
    {
      $branch_offices = [];
  	  $branchOfficeList = array();
  		foreach (Auth::user()->roles as $roles) {
  			if($roles->branch_office_id == null)
  			{
  				$branchOfficeList = \App\BranchOffice::where('is_active', '=', 1)->get();
  				break;
  			}
  			else if($roles->enabled == 1)
  			{
  				array_push($branchOfficeList, \App\BranchOffice::find($roles->branch_office_id));
  			}
  		}
      foreach ($branchOfficeList as $a) {
        $branch_offices[] = $a->id;
      }
      return $branch_offices;
    }
}

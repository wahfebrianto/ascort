<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BranchOffice extends Model
{
    protected $table = "branch_offices";
    protected $fillable = ['branch_name', 'address', 'state', 'city', 'zipcode', 'phone_number'];

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

    public function users(){
        return $this->hasMany('App\User');
    }

    // ------------------------------------------ END OF RELATIONSHIP -----------------------------------------------

    public static function getBranchOffices(){
        return BranchOffice::where('is_active', '=', 1)->get();
    }
	public static function getBranchOfficeFromId($id){
        $found = BranchOffice::where('id', '=', $id)->where('is_active', '=', 1)->get();
        return (count($found)>0 ? $found[0] : null);
    }

    public static function getBranchOffices_forDropDown()
    {
      $branch_offices = [];
      $allbranches = Auth::user()->branchOffices;
      foreach ($allbranches as $a) {
        $branch_offices[$a->id] = $a->branch_name;
      }
      return $branch_offices;
    }

    public static function getBranchOfficesID()
    {
      $branch_offices = [];
      $allbranches = Auth::user()->branchOffices;
      foreach ($allbranches as $a) {
        $branch_offices[] = $a->id;
      }
      return $branch_offices;
    }
}

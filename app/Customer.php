<?php

namespace App;

use Carbon\Carbon;
use Doctrine\DBAL\Driver\IBMDB2\DB2Statement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class Customer extends Model
{
    protected $table = "customers";
    protected $fillable = ['name','NIK', 'NPWP',  'address','email','phone_number', 'handphone_number',  'gender',  'state', 'city', 'zipcode',
        'cor_address', 'cor_state', 'cor_city', 'cor_zipcode', 'last_transaction', 'branch_office_id'];
    protected $dates = ['last_transaction'];

    // ---------------------------------------------- RELATIONSHIP --------------------------------------------------

    public function sales(){
        return $this->hasMany('App\Sale', 'customer_id', 'id');
    }

    public function branchOffice()
    {
        return $this->belongsTo('App\BranchOffice', 'branch_office_id');
    }

    // ------------------------------------------ END OF RELATIONSHIP -----------------------------------------------

    public function scopeIsActive($query) {
        $query->where('is_active', '=', '1');
    }

    public function setLastTransactionAttribute($date){
        $this->attributes['last_transaction'] = Carbon::createFromFormat('Y-m-d H:i:s', $date);
    }

    public function getLastTransactionAttribute($date){
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }


    public function getSalesAttribute(){
        return $this->sales()->where('is_active', '=', 1)->get();
    }

    public function getBranchOfficeNameAttribute()
    {
        return $this->branchOffice()->first()['name'];
    }

    public static function getCustomerFromId($id){
        if(Auth::getUser()->hasRole('otor'))
        {
          $found = Customer::where('id', '=', $id)->where('is_active', '=', 0)->get();
        }
        else {
          $found = Customer::where('id', '=', $id)->where('is_active', '=', 1)->get();
        }
        return (count($found)>0 ? $found[0] : null);
    }

    public static function getCustomers(){
        return Customer::where('is_active', '=', 1)->get();
    }

    public static function getCustomers_ForDropDown(){
        $customers = [];
        $allCustomers = Customer::getCustomers();
        foreach($allCustomers as $a){
            $customers[$a->id] = $a->name . " [" . $a->branchOffice->branch_name . "]";
        }
        return $customers;
    }

    public static function getColumnsArray()
    {
        return (new Customer())->getFillable();
    }

    /*
    public static function updateSPAJ($customer){
        $customer->update(['SPAJ' => str_pad((string) $customer->id, 9, '0', STR_PAD_LEFT)]);
    }
    */

    public static function deleteInactiveCustomers(){
        $timespan = config('delete_cust_timespan');
        $inactive = Customer::where('is_active', '=', 1)->where('last_transaction', '<', Carbon::now()->subMonth($timespan))->get();
        foreach($inactive as $cust){

            //reminder for 3 days
            Reminder::create([
                'reminder_for' => 'owner',
                'start_date' => Carbon::now()->format('d/m/Y'),
                'end_date' => Carbon::now()->addDays(3)->format('d/m/Y'),
                'content' => 'Inactive Customer ' . $cust->name . ' deleted.',
                'respond_link' => '#'
            ]);

            $cust->delete();
        }
        return $inactive;
    }

    public static function updateLastTransaction(Customer $customer){
        $customer->update(['last_transaction' => Carbon::now()->format('Y-m-d H:i:s')]);
    }
}

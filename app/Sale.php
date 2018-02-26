<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Nayjest\Grids\EloquentDataProvider;
use Auth;

class Sale extends Model
{
    protected $table = "sales";
    protected $fillable = ['customer_id','agent_id', 'agent_commission', 'product_id', 'number','customer_name',
                            'MGI', 'MGI_month', 'MGI_start_date', 'nominal', 'interest', 'additional',
                            'bank', 'bank_branch', 'account_name', 'account_number', 'branch_office_id'];
    protected $dates = ['MGI_start_date'];
    protected $appends = ['product_name', 'product_code', 'agent_commission_value', 'FYP', 'customer_name'];

    // ---------------------------------------------- RELATIONSHIP --------------------------------------------------

    public function agent(){
        return $this->belongsTo('App\Agent', 'agent_id');
    }

    public function product(){
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function customer(){
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function branchOffice()
    {
        return $this->belongsTo('App\BranchOffice', 'branch_office_id');
    }

    // ------------------------------------------ END OF RELATIONSHIP -----------------------------------------------

    public function setMGIStartDateAttribute($date){
        $this->attributes['MGI_start_date'] = Carbon::createFromFormat('d/m/Y', $date);
    }

    public function getMGIStartDateAttribute($date){
        return Carbon::parse($date)->format('d/m/Y');
    }

    public function setCustomerDobAttribute($date){
        $this->attributes['customer_dob'] = Carbon::createFromFormat('d/m/Y', $date);
    }

    public function getCustomerDobAttribute($date){
        return Carbon::parse($date)->format('d/m/Y');
    }


    public function getFormattedNominalAttribute() {
        return \App\Money::format('%(.2n', $this->nominal);
    }

    public function getFormattedAdditionalAttribute() {
        return \App\Money::format('%(.2n', $this->additional);
    }

    public function getFormattedFYPAttribute() {
        return \App\Money::format('%(.2n', $this->FYP);
    }

    public function getDueDateAttribute(){
        return Carbon::createFromFormat('d/m/Y', $this->MGI_start_date)->addMonthNoOverflow($this->MGI_month)->format('d/m/Y');
    }

    public function getIsEditableAttribute() {
        $now = Carbon::today();
        $mgi_start_date = Carbon::createFromFormat('d/m/Y', $this->MGI_start_date);
        $arr = \App\CommissionReport::getDatesOfPeriodCode(\App\CommissionReport::getPeriodCodeFromDate($mgi_start_date));
        $end_date = $arr['end_date'];
        return $now < $end_date;
    }

    public function scopeOfPeriod($query, $period, $month, $year)
    {
        $arr = \App\CommissionReport::getDatesOfPeriod($period, $month, $year);
        $start_date = $arr['start_date'];
        $end_date = $arr['end_date'];
        return $query
            ->whereBetween('created_at', [$start_date, $end_date]);
    }
    
    public function scopeMGIBetween($query, $period, $month, $year)
    {
        $arr = \App\CommissionReport::getDatesOfPeriod($period, $month, $year);
        $start_date = $arr['start_date'];
        $end_date = $arr['end_date'];
        return $query
            ->whereBetween('MGI_start_date', [$start_date, $end_date]);
    }

    public function scopeMgiDateBetween($query, $start_date, $end_date) {
        return $query
            ->whereBetween('MGI_start_date', [$start_date, $end_date]);
    }

    public function scopeIsActive($query)
    {
        return $query->where('is_active', '=', 1);
    }

    public function scopeDueOnDay($query, $day)
    {
        return $query->whereRaw("DATEDIFF(DATE_ADD(`sales`.`MGI_start_date`, INTERVAL `sales`.`MGI_month` MONTH), NOW()) = $day");
    }

    public function scopeDueBetween($query, Carbon $date1, Carbon $date2)
    {
        return $query->whereRaw("DATE_ADD(`sales`.`MGI_start_date`, INTERVAL `sales`.`MGI_month` MONTH) BETWEEN STR_TO_DATE(?, '%d/%m/%Y') AND STR_TO_DATE(?, '%d/%m/%Y')", [$date1->format('d/m/Y'), $date2->format('d/m/Y')]);
    }

    public function getCustomer_IdAttribute(){
        return ($this->customer_id == null ? "-" : $this->customer_id);
    }

    public static function getSaleCommissionPercentage($sale) {
        return $sale->agent->agent_position->saleCommissionPercentage != null ?
            $sale->agent->agent_position->saleCommissionPercentage->percentage :
            0;
    }

    public function getAgentNameAttribute()
    {
        return $this->agent()->getResults()['name'];
    }
    
    public function getCustomerNameAttribute()
    {
        return $this->customer()->getResults()['name'];
    }

    public function getProductNameAttribute()
    {
        return $this->product()->getResults()['product_name'];
    }

    public function getProductCodeAttribute()
    {
        return $this->product()->getResults()['product_code'];
    }

    public function getFYPAttribute()
    {
        return $this->nominal * ($this->MGI_month / 12);
    }

    public function getAgentCommissionValueAttribute()
    {
        return ($this->agent_commission / 100) * $this->FYP;
    }

    public function getBranchOfficeNameAttribute()
    {
        return $this->branchOffice()->first()['name'];
    }

    public static function getSaleFromId($id)
    {
        $found = Sale::where('id', '=', $id)->get();
        return (count($found)>0 ? $found[0] : null);
    }

    public static function getColumnsArray()
    {
        return (new Sale())->getFillable();
    }

    public static function getIndexDataProvider($enabledOnly = 1)
    {
        // grids filter and sorting workaround -> https://github.com/Nayjest/Grids/issues/41
        return new EloquentDataProvider(Sale::join('agents', 'agents.id', '=', 'sales.agent_id')->select('sales.*')->addSelect('agents.name')->where('sales.is_active', $enabledOnly)->whereIn('sales.branch_office_id', \App\BranchOffice::getBranchOfficesID()));
    }

    public static function getDashboardDataProvider()
    {
        // grids filter and sorting workaround -> https://github.com/Nayjest/Grids/issues/41
        return new EloquentDataProvider(
            Sale
            ::join('agents', 'agents.id', '=', 'sales.agent_id')
            ->select('sales.*')
            ->addSelect('agents.name')
            ->where('sales.is_active', 1)
            ->whereIn('sales.branch_office_id', \App\BranchOffice::getBranchOfficesID())
            ->orderBy('sales.created_at', 'desc')
            ->take(10)
        );
    }

    public static function disableLastYearSales(){
        $lastYear = Sale::where('is_active', '=', 1)->where('MGI_start_date', '<', Carbon::create(Carbon::now()->year, 1, 1))->get();
        foreach($lastYear as $sale){
            if(Carbon::createFromFormat('d/m/Y', $sale->MGI_start_date)->addMonthNoOverflow($sale->MGI_month) < Carbon::now()){
                $sale->is_active = 0;
                $sale->update();
                $sale->push();
            }
        }
    }

    public static function getAllSalesByAgentTeamNameBuilder($team_name)
    {
        return Sale
            ::isDueNextMonth()
            ->join('agents', 'agents.id', '=', 'sales.agent_id')
            ->where('agents.team_name', '=', $team_name)
            ->where('sales.is_active', '=', 1)
            ->orderBy('agents.id');
    }

    public static function updateMGIMonth($sale){
        $sale->update(['MGI_month' => config('MGIs.' . $sale->MGI)[1]]);
    }
}

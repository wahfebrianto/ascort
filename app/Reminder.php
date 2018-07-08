<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Reminder extends Model
{
    protected $table = "reminders";
    protected $fillable = ['title', 'reminder_for', 'start_date', 'end_date', 'subject', 'content', 'respond_link'];
    protected $dates = ['start_date', 'end_date'];

    // ----------------------------------------------------------------------------------------------------------------


    public function setStartDateAttribute($date){
        $this->attributes['start_date'] = Carbon::createFromFormat('d/m/Y', $date);
    }

    public function getStartDateAttribute($date){
        return Carbon::parse($date)->format('d/m/Y');
    }

    public function setEndDateAttribute($date){
        $this->attributes['end_date'] = Carbon::createFromFormat('d/m/Y', $date);
    }

    public function getEndDateAttribute($date){
        return Carbon::parse($date)->format('d/m/Y');
    }

    public static function getReminderById($id){
        return Reminder::findOrFail($id);
    }


    public static function getActiveRemindersBuilder(){
        return Reminder::where('is_active', '=', 1)
            ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
            ->where('end_date', '>=', Carbon::now()->subDays(2)->format('Y-m-d'));
    }

    public static function getActiveRemindersForAdminBuilder(){
        return Reminder::getActiveRemindersBuilder()
            ->where('reminder_for', '=', Auth::user()->id);
    }

    public static function getActiveRemindersForOwnerBuilder(){
        return Reminder::getActiveRemindersBuilder()
            ->where('reminder_for', '=', 'owner');
    }

    public static function getEvalReminders(){
        return Reminder::getActiveRemindersForAdminBuilder()
            ->where('subject', '=', 'eval')
            ->get();
    }

    public static function getExpReminders(){
        return Reminder::getActiveRemindersBuilder()
            ->where('subject', '=', 'expire')
            ->where('reminder_for', '=', Auth::user()->roles->first()->branch_office_id)
            ->get();
    }

    public static function getRolloverReminders(){
        return Reminder::getActiveRemindersForAdminBuilder()
            ->where('subject', '=', 'rollover')
            ->get();
    }

    public static function getIgnoredRolloverReminders(){
        return Reminder::where('is_active', '=', 1)
            ->where('reminder_for', '=', Auth::user()->id)
            ->where('subject', '=', 'rollover')
            ->where('end_date', '=', Carbon::now()->format('Y-m-d'))
            ->get();
    }

    public static function getApprovalReminders(){
        return Reminder::getActiveRemindersForAdminBuilder()->where('subject', '=', 'approval')->get();
    }

    public static function inactivateReminder($reminder){
        $reminder->is_active = 0;
        $reminder->update();
    }


}

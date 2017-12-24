<?php

namespace App\Console;

use App\Action;
use App\Agent;
use App\CommissionReport;
use App\Customer;
use App\Money;
use App\Reminder;
use App\Sale;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Repositories\AuditRepository as Audit;
use App\User;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();


        $schedule->call(function(){
            Customer::deleteInactiveCustomers();
            Audit::log(User::getOwnerId(), request()->ip(), 'scheduler', 'Deleted inactive customers.');
        })->daily()->name('inactive_cust')->withoutOverlapping();


        $schedule->call(function(){
            Sale::disableLastYearSales();
            CommissionReport::deleteLastYearReport();
            Audit::log(User::getOwnerId(), request()->ip(), 'scheduler', 'Deleted last year sales.');
        })->cron('0 0 31 1 * *')->name('last_year_sales')->withoutOverlapping();
        // cron : time 00:00 day 31 in january each year


        // -------------------------------------------- EVALUATE AGENTS ------------------------------------------------
        $schedule->call(function(){
            Agent::evaluateAgents();
            Audit::log(User::getOwnerId(), request()->ip(), 'scheduler', 'Evaluated agents.');
            //show in dashboard
        })->daily()->when(function(){
            //June 30th - July 5th
            if(Carbon::now() >= Carbon::create(Carbon::now()->year, 6, 30) && Carbon::now() <= Carbon::create(Carbon::now()->year, 7, 5)){
                return true;

            }else{
                //if now = december and DayOfMonth > dec 30th
                if(Carbon::now()->month == 12 && Carbon::now()->day >= 30){
                    return true;
                    //if now == january and DayOfMonth < jan 10th
                }elseif(Carbon::now()->month == 1 && Carbon::now()->day <= 10){
                    return true;
                }
            }
            return false;
        })->name('evaluate_agents')->withoutOverlapping();


        // ---------------------------------------------- DEMOTIONS ----------------------------------------------------
        $schedule->call(function(){
            $evaluatedAgents = Agent::evaluateAgents();
            foreach($evaluatedAgents as $agent){
                $content = [
                    'table' => 'agents',
                    'id' => $agent->id,
                    'message' => 'Agent <b>' . $agent->name . '</b> [' . $agent->agent_position->name .
                                '] didn\'t meet target evaluation. <br/>Do you want to change agent\'s position?'
                ];
                $reminder = Reminder::create([
                    'title' => 'Agent Evaluation Reminder',
                    'reminder_for' => 'admin',
                    'start_date' => Carbon::now()->format('d/m/Y'),
                    'end_date' => Carbon::now()->addDays(4)->format('d/m/Y'),
                    'subject' => 'eval',
                    'content' => json_encode($content),
                    'respond_link' => 'dashboard/changeagentposition'
                ]);

                Audit::log(User::getOwnerId(), request()->ip(), 'scheduler', 'Save reminder of agent evaluation.', $reminder->toArray());
            }

        })->daily()->when(function(){
            //June 30th + 3 weeks
            if(Carbon::now() == Carbon::create(Carbon::now()->year, 6, 30)->addWeeks(3)){
                return true;

            }else{
                //December 31th Last Year + 3 weeks
                if(Carbon::now() == Carbon::create(Carbon::now()->year-1, 12, 31)->addWeeks(3)){
                    return true;
                }
            }
            return false;
        })->name('demote_agents_reminder')->withoutOverlapping();



        // ---------------------------------- CREATE REMINDER FOR ROLLOVER SALES ---------------------------------------
        $schedule->call(function(){
            Audit::log(User::getOwnerId(), request()->ip(), 'scheduler', 'Rollover sales reminder.');
            // due exactly 7 days from now
            foreach(Sale::isActive()->dueOnDay(7)->get() as $sale){
                $content = [
                    'table' => 'sales',
                    'id' => $sale->id,
                    'message' => 'Sale with ID #' . $sale->id . ' for Customer <b>' . $sale->customer->name .
                                '</b> by Agent <i>' . $sale->agent->name . '</i> with premium ' .
                                Money::format('%(.2n', $sale->premium) . ' for ' . $sale->MGI_month .
                                ' month(s) is due on <b>' . $sale->DueDate . '</b>. <br/>Do you want to rollover?'
                ];
                Reminder::create([
                    'title' => 'Rollover Sales',
                    'reminder_for' => 'admin',
                    'start_date' => Carbon::now()->format('d/m/Y'),
                    'end_date' => Carbon::now()->addDays(7)->format('d/m/Y'),
                    'subject' => 'rollover',
                    'content' => json_encode($content),
                    'respond_link' => 'sales/rollover/' . $sale->id
                ]);
            }
        })->daily()->name('due_sales')->withoutOverlapping();


        // ----------------------------------------- AUTO ROLLOVER SALES -----------------------------------------------
        $schedule->call(function(){
            Audit::log(User::getOwnerId(), request()->ip(), 'scheduler', 'Auto-rollover sale.', Reminder::getIgnoredRolloverReminders());
            foreach(Reminder::getIgnoredRolloverReminders() as $reminder){
                $oldSale = Sale::find(json_decode($reminder->content)->id);

                // rollover sale
                $sale = new Sale();
                $sale->agent_id = $oldSale->agent_id;
                $sale->product_id = $oldSale->product_id;
                $sale->number = $oldSale->number;
                $sale->customer_id = $oldSale->customer_id;
                $sale->customer_name = $oldSale->customer_name;
                $sale->customer_dob = $oldSale->customer_dob;
                $sale->MGI = $oldSale->MGI;
                $sale->MGI_month = $oldSale->MGI_month;
                $sale->currency = $oldSale->currency;
                $sale->MGI_start_date = $oldSale->MGI_start_date;
                $sale->nominal = $oldSale->nominal;
                $sale->interest = $oldSale->interest;
                $sale->additional = $oldSale->additional;
                $sale->SPAJ = $oldSale->SPAJ;
                $sale->save();

                $sale->agent_commission = Sale::getSaleCommissionPercentage($sale->id);
                $sale->save();

                Sale::updateMGIMonth($sale);

                // inactivate reminder
                Reminder::inactivateReminder(Reminder::getReminderById($reminder->id));
            }

        })->daily()->name('rollover_reminder_end')->withoutOverlapping();


        // ------------------------------------------- EXECUTE ACTIONS -------------------------------------------------
        $schedule->call(function(){
            foreach(Action::getTodaysAction() as $action){
                DB::raw($action->action);
            }

            Audit::log(User::getOwnerId(), request()->ip(), 'scheduler', 'Executing delayed actions.', Action::getTodaysAction()->toArray());

        })->daily()->name('execute_actions')->withoutOverlapping();


        // ---------------------------------------- UPDATE HOLIDAY DATES -----------------------------------------------
        $schedule->call(function(){
            \App\Holiday::loadFromGoogleCalendar();
            Audit::log(User::getOwnerId(), request()->ip(), 'scheduler', 'Update holidays from Google Calendar API.');
        })->cron('1 0 1 1 * *')->name('update_holidays')->withoutOverlapping();

    }
}

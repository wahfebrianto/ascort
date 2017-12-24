<?php
/**
 * Created by PhpStorm.
 * User: Maya
 * Date: 26-Mar-16
 * Time: 3:02 AM
 */

namespace App\Calculations;


use App\Holiday;
use App\Sale;
use Carbon\Carbon;

class Interest {
    public static function getInterest($sale){
        return Interest::calculate($sale);
    }

    private static function calculate($sale){
        $daysInYear = Carbon::parse('last day of December ' . Carbon::now()->year)->dayOfYear;
        $interestPerDay = ($sale->nominal * ($sale->interest / 100)) / $daysInYear;
        $paymentDay = Carbon::createFromFormat('d/m/Y', $sale->MGI_start_date)->day;

        return Interest::interestRecursive($sale, $paymentDay, Carbon::createFromFormat('d/m/Y', $sale->MGI_start_date), $interestPerDay, []);
    }

    private static function interestRecursive(Sale $sale, $paymentDay, Carbon $lastMonthPaymentDate, $interestPerDay, $arrInterest){
        $endDate = Carbon::createFromFormat('d/m/Y', $sale->MGI_start_date)->setTime(0,0,0)->addMonths($sale->MGI_month);

        if(Carbon::create($lastMonthPaymentDate->year, $lastMonthPaymentDate->month, $paymentDay, 0, 0, 0) != $endDate){
            $nextPaymentDate =
                Carbon::create($lastMonthPaymentDate->month == 12? $lastMonthPaymentDate->year + 1 : $lastMonthPaymentDate->year,
                    $lastMonthPaymentDate->month == 12? 1 : $lastMonthPaymentDate->month + 1,
                    $paymentDay, 0, 0, 0);
            $originalPaymentDate =
                Carbon::create($lastMonthPaymentDate->month == 12? $lastMonthPaymentDate->year + 1 : $lastMonthPaymentDate->year,
                    $lastMonthPaymentDate->month == 12? 1 : $lastMonthPaymentDate->month + 1,
                    $paymentDay, 0, 0, 0);

            if(Holiday::isCuti($nextPaymentDate) == true){
                $nextPaymentDate = Holiday::getPreviousWorkDay($nextPaymentDate);
            }elseif(Holiday::isHoliday($nextPaymentDate) == true){
                $nextPaymentDate = Holiday::getNextWorkDay($nextPaymentDate);
            }elseif($nextPaymentDate->isWeekendy()){
                $nextPaymentDate = Holiday::getNextWorkDay($nextPaymentDate);
            }

            if($originalPaymentDate == $endDate){
                $daysCount = $originalPaymentDate->diffInDays($lastMonthPaymentDate);
            }else{
                $daysCount = $nextPaymentDate->diffInDays($lastMonthPaymentDate);
            }
            $daysName = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            $arrInterest[] = [$daysName[$nextPaymentDate->dayOfWeek], $nextPaymentDate->format('d/m/Y'), $daysCount, $daysCount * $interestPerDay];


            $arrInterest = Interest::interestRecursive($sale, $paymentDay, $nextPaymentDate, $interestPerDay, $arrInterest);

        }

        return $arrInterest;
    }

} 
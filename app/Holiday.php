<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use GuzzleHttp\Client;

class Holiday extends Model
{
    protected $table = "holidays";
    protected $fillable = ['cal_id', 'year', 'date', 'type', 'description'];
    protected $dates = ['date'];

    public function setDateAttribute($date){
        try {
            $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $date);
        } catch (\Exception $e) {
            $this->attributes['date'] = $date;
        }
    }

    public function getDateAttribute($date){
        return Carbon::parse($date)->format('d/m/Y');
    }

    /**
     *
     * Get all indonesian holidays data from Google Calendar API
     * @return array
     */
    public static function getFromGoogleCalendar()
    {
        $api_key = config('google_api.api_key');
        $calendar_key = config('google_api.calendar.indonesian_holidays');
        $url = config('google_api.calendar.url');
        $api_url = $url . $calendar_key . '/events?key=' . $api_key;

        $client = new Client();
        $res = $client->get($api_url, ['verify' => false]);
        if($res->getStatusCode() == 200) {
            $array = json_decode($res->getBody(), true); // true -> return assoc array
            return $array;
        }
        return [];
    }

    public static function loadFromGoogleCalendar() {
        $array = self::getFromGoogleCalendar();
        $cur_year = Carbon::now()->year;
        if(array_key_exists('items', $array)) {
            foreach ($array['items'] as $data) {
                $date = Carbon::createFromFormat('Y-m-d', $data['start']['date']);
                if($date->year == $cur_year) {
                    $exist = (self::where('cal_id', '=', $data['id'])->count() > 0);
                    if (!$exist) {
                        $h = self::create([
                            'cal_id' => $data['id'],
                            'year' => $date->year,
                            'date' => $date,
                            'type' => strpos($data['summary'], 'Cuti') !== false ? 'Cuti' : 'Normal',
                            'description' => $data['summary']
                        ]);
                        $h->save();
                    }
                }
            }
        }
    }

    public static function isCuti(Carbon $date){
        $holiday = Holiday::where('date', '=', $date->format('Y-m-d'))
            ->where('type', '=', 'Cuti')->get();
        return count($holiday) > 0? true : false;
    }

    public static function isHoliday(Carbon $date){
        $holiday = Holiday::where('date', '=', $date->format('Y-m-d'))->get();
        return count($holiday) > 0? true : false;
    }

    public static function getNextWorkDay(Carbon $date){
        $date = Carbon::create($date->year, $date->month, $date->day +1);
        if(Holiday::isCuti($date)){
            $date = Holiday::getPreviousWorkDay($date);
        }else{
            // not Cuti
            if(Holiday::isHoliday($date) || $date->isWeekend())
                $date = Holiday::getNextWorkDay($date);
        }
        return $date;
    }

    public static function getPreviousWorkDay(Carbon $date){
        $date = Carbon::create($date->year, $date->month, $date->day -1);
        if(Holiday::isHoliday($date) || $date->isWeekend())
            $date = Holiday::getPreviousWorkDay($date);
        return $date;
    }
}

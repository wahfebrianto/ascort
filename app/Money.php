<?php
/**
 * Created by PhpStorm.
 * User: harto
 * Date: 3/1/2016
 * Time: 4:35 PM
 */

namespace App;


class Money
{

    const CURRENCY = 'Rp';


    /**
     * http://php.net/manual/en/function.money-format.php fix for Windows and spec is the same
     * @param $format
     * @param $number
     * @return mixed
     */
    public static function format($format, $number)
    {
		return self::CURRENCY . ' ' . number_format($number, 2, ',', '.');
    }

}
<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nayjest\Grids\EloquentDataProvider;

class Audit extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'ip_address', 'category', 'message', 'data', 'data_parser', 'replay_route'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getUserDisplayNameAttribute()
    {
        return $this->user()->getResults()['username'];
    }

    public static function getIndexDataProvider()
    {
        // grids filter and sorting workaround -> https://github.com/Nayjest/Grids/issues/41
        return new EloquentDataProvider(Audit::join('users', 'users.id', '=', 'audits.user_id')->select('audits.*')->addSelect('users.username'));
    }

}

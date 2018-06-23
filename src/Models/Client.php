<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 12:29 AM.
 */

namespace KgBot\LaravelDeploy\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'ldp_clients';

    protected $fillable = [

        'token',
        'source',
        'name',
        'active',
        'script_source',
        'auto_deploy',
    ];

    protected $casts = [

        'active'      => 'boolean',
        'auto_deploy' => 'boolean',
    ];

    public function setTokenAttribute($value)
    {
        $this->attributes['token'] = bcrypt($value);
    }

    public function changeStatus()
    {
        $this->update([

            'active' => ! $this->active,
        ]);
    }

    public function changeAutoDeploy()
    {
        $this->update([

            'auto_deploy' => ! $this->auto_deploy,
        ]);
    }

    public function scopeActive()
    {
        return $this->where('active', true);
    }
}

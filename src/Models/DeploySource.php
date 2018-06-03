<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 12:29 AM
 */

namespace KgBot\LaravelDeploy\Models;


use Illuminate\Database\Eloquent\Model;

class DeploySource extends Model
{
    protected $fillable = [

        'token',
        'source',
        'name',
        'active',
        'script_source',
    ];
}
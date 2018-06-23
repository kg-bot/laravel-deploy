<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 12:40 AM.
 */

namespace KgBot\LaravelDeploy\Http\Controllers\Front;

use KgBot\LaravelDeploy\Http\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('laravel-deploy::dashboard');
    }
}

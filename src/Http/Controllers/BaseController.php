<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 12:44 AM
 */

namespace KgBot\LaravelDeploy\Http\Controllers;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
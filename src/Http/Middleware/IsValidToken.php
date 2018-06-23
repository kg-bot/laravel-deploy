<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 12:25 AM.
 */

namespace KgBot\LaravelDeploy\Http\Middleware;

use Closure;
use KgBot\LaravelDeploy\Models\Client;
use KgBot\LaravelDeploy\Exceptions\InvalidClientException;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class IsValidToken extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->get('_token');

        if ($token) {
            $client = Client::where([

                ['token', $token],
                ['active', true],
            ])->first();

            if (! $client) {
                throw new InvalidClientException();
            }

            return $next($request);
        }
    }
}

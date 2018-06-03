<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 12:25 AM
 */

namespace KgBot\LaravelDeploy\Http\Middleware;

use Closure;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use KgBot\LaravelDeploy\Exceptions\InvalidClientException;
use KgBot\LaravelDeploy\Models\DeploySource;


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
    public function handle( $request, Closure $next )
    {
        $token = $request->get( '_token' );

        if ( $token ) {

            $client = DeploySource::where( [

                [ 'token', $token ],
                [ 'source', $request->getHost() ],
                [ 'active', true ],
            ] )->first();

            if ( !$client ) {

                throw new InvalidClientException();
            }

            return $next( $request );
        }
    }
}
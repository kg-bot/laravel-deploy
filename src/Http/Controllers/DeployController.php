<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 12:40 AM
 */

namespace KgBot\LaravelDeploy\Http\Controllers;

use Illuminate\Http\Request;
use KgBot\LaravelDeploy\Exceptions\UnableToReadScriptFile;
use KgBot\LaravelDeploy\Jobs\DeployJob;
use KgBot\LaravelDeploy\Models\Client;

class DeployController extends BaseController
{
    public function request( Request $request )
    {
        $client = Client::where( [

            [ 'token', $request->get( '_token' ) ],
            [ 'active', true ],
            [ 'auto_deploy' => true ],
        ] )->first();

        $filename    = $client->script_source;
        $script_file = base_path( $filename );

        if ( !file_exists( $script_file ) ) {

            throw new UnableToReadScriptFile();

        }

        dispatch( new DeployJob( $client, $script_file ) )->onQueue( config( 'laravel-deploy.queue', 'default' ) );

        return response()->json( 'success' );
    }
}
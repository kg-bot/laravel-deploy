<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 12:40 AM
 */

namespace KgBot\LaravelDeploy\Http\Controllers;


use App\Jobs\DeployJob;
use Illuminate\Http\Request;
use KgBot\LaravelDeploy\Exceptions\UnableToReadScriptFile;
use KgBot\LaravelDeploy\Models\DeploySource;

class DeployController extends BaseController
{
    public function request( Request $request )
    {
        if ( config( 'laravel-deploy.run_deploy' ) ) {

            $client = DeploySource::where( [

                [ 'token', $request->get( '_token' ) ],
                [ 'active', true ],
            ] )->first();

            $filename    = $client->script_source;
            $script_file = base_path( $filename );

            if ( !file_exists( $script_file ) ) {

                throw new UnableToReadScriptFile();

            }

            dispatch( new DeployJob( $client, $script_file ) )->onQueue( config( 'laravel-deploy.queue', 'default' ) );
        }

        return response()->json( 'success' );
    }
}
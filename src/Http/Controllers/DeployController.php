<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 12:40 AM
 */

namespace KgBot\LaravelDeploy\Http\Controllers;


use Illuminate\Http\Request;
use KgBot\LaravelDeploy\Events\LaravelDeployFinished;
use KgBot\LaravelDeploy\Events\LaravelDeployStarted;
use KgBot\LaravelDeploy\Exceptions\UnableToReadScriptFile;
use KgBot\LaravelDeploy\Models\DeploySource;
use Symfony\Component\Process\Process;

class DeployController extends BaseController
{
    public function request( Request $request )
    {
        $client = DeploySource::where( [

            [ 'token', $request->get( '_token' ) ],
            [ 'active', true ],
        ] )->first();

        $filename    = $client->script_source;
        $script_file = base_path( $filename );

        if ( !file_exists( $script_file ) ) {

            throw new UnableToReadScriptFile();

        }

        $process = new Process( $script_file );
        event( new LaravelDeployStarted( $client ) );
        $process->start();
        event( new LaravelDeployFinished( $client ) );

        return response()->json( 'success' );
    }
}
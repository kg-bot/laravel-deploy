<?php

namespace KgBot\LaravelDeploy\Http\Controllers\Front\Ajax;

use Dubture\Monolog\Reader\LogReader;
use KgBot\LaravelDeploy\Http\Controllers\BaseController;
use KgBot\LaravelDeploy\Jobs\DeployJob;
use KgBot\LaravelDeploy\Models\Client;

class SettingsController extends BaseController
{
    protected $lf_characters = [ '\r\n', '\n\r', '\r', '\n' ];

    /**
     * Return last deploy log
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function lastLog()
    {

        $log_file_name = config( 'laravel-deploy.log_file_name', 'laravel-log' );
        $path          = storage_path( 'logs/' . $log_file_name );

        if ( file_exists( $path ) ) {

            $reader  = new LogReader( $path );
            $pattern =
                '/\[(?P<date>.*)\] (?P<logger>[\w-\s]+).(?P<level>\w+): (?P<message>[^\[\{]+) (?P<context>[\[\{].*[\]\}]) (?P<extra>[\[\{].*[\]\}])/';
            $reader->getParser()->registerPattern( 'newPatternName', $pattern );
            $reader->setPattern( 'newPatternName' );

        } else {

            return response()->json( 'Log file path does not exist, check your configuration and try again.', 404 );
        }

        if ( count( $reader ) ) {

            $log              = $reader[ count( $reader ) - 2 ];
            $log[ 'message' ] = str_replace( $this->lf_characters, '<br />', $log[ 'message' ] );

            return response()->json( [ 'log' => $log ] );

        } else {

            return response()->json( 'Log is empty, deploy something!!!', 404 );
        }
    }

    /**
     * Return collection of all logs
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allLogs()
    {
        $log_file_name = config( 'laravel-deploy.log_file_name', 'laravel-log' );
        $path          = storage_path( 'logs/' . $log_file_name );

        if ( file_exists( $path ) ) {

            $reader  = new LogReader( $path );
            $pattern =
                '/\[(?P<date>.*)\] (?P<logger>[\w-\s]+).(?P<level>\w+): (?P<message>[^\[\{]+) (?P<context>[\[\{].*[\]\}]) (?P<extra>[\[\{].*[\]\}])/';
            $reader->getParser()->registerPattern( 'newPatternName', $pattern );
            $reader->setPattern( 'newPatternName' );

        } else {

            return response()->json( 'Log file path does not exist, check your configuration and try again.', 404 );
        }

        if ( count( $reader ) ) {

            return response()->json( [ 'logs' => collect( $reader ) ] );

        } else {

            return response()->json( 'Log is empty, deploy something!!!', 404 );
        }
    }

    public function deployNow( Client $client )
    {

        dispatch( new DeployJob( $client, base_path( $client->script_source ) ) );

        return response()->json( 'success' );
    }

    public function index()
    {
        $clients  = Client::Active()->get();
        $settings = [

            'quick_deploy' => config( 'laravel-deploy.run_deploy' ),
        ];

        return response()->json( compact( 'clients', 'settings' ) );
    }
}

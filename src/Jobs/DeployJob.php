<?php

namespace KgBot\LaravelDeploy\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use KgBot\LaravelDeploy\Events\LaravelDeployFailed;
use KgBot\LaravelDeploy\Events\LaravelDeployFinished;
use KgBot\LaravelDeploy\Events\LaravelDeployStarted;
use KgBot\LaravelDeploy\Models\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Process\Process;

class DeployJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 240;
    public $tries   = 1;

    public $client;
    public $script_file;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( Client $client, string $script_file )
    {
        $this->client      = $client;
        $this->script_file = $script_file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logger = new Logger( 'laravel-deploy-logger' );

        $log_file_name = config( 'laravel-deploy.log_file_name', 'laravel-log' );
        $stream        = new StreamHandler( storage_path( '/logs/' . $log_file_name ), Logger::DEBUG );


        $logger->pushHandler( $stream );

        $command = 'echo \'' . config( 'laravel-deploy.user.password' );
        $command .= '\' | sudo -S -u ' . config( 'laravel-deploy.user.username' );
        $command .= ' sh ' . $this->script_file;

        $command = 'sh ' . $this->script_file;
        echo $command;

        $process = new Process( $command );
        $process->setTimeout( 300 );
        $message = '';
        $error   = '';
        event( new LaravelDeployStarted( $this->client, $command ) );

        ini_set( 'max_execution_time', 200 );
        $process->run( function ( $type, $buffer ) use ( &$message, &$error ) {

            if ( Process::ERR === $type ) {

                $error .= $buffer . '\r\n';

            } else {

                $message .= $buffer . '\r\n';
            }
        } );

        if ( $message !== '' ) {

            $logger->info( $message, [
                'client' => json_encode( $this->client ),
                'script' => $this->script_file,
            ] );
            event( new LaravelDeployFinished( $this->client, $message ) );

        } else if ( $error !== '' ) {

            $logger->critical( $error, [
                'client' => json_encode( $this->client ),
                'script' => $this->script_file,
            ] );
            event( new LaravelDeployFailed( $this->client, $error ) );
        }

    }
}

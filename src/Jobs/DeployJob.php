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
use Monolog\Formatter\LineFormatter;
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
     * @var Logger
     */
    protected $logger;

    /**
     * @var Process
     */
    protected $process;

    /**
     * @var string $command Command to be executed
     */
    protected $command;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( Client $client, string $script_file )
    {
        $this->client      = $client;
        $this->script_file = $script_file;
        $this->setLogger();
        $this->setProcess();
    }

    protected function setLogger()
    {
        $this->logger = new Logger( 'laravel-deploy-logger' );

        $log_file_name = config( 'laravel-deploy.log_file_name', 'laravel-log' );
        $stream        = new StreamHandler( storage_path( '/logs/' . $log_file_name ), Logger::DEBUG );
        $formatter     = tap( new LineFormatter( null, null, true, true ), function ( $formatter ) {
            $formatter->includeStacktraces();
        } );
        $stream->setFormatter( $formatter );


        $this->logger->pushHandler( $stream );
    }

    protected function setProcess()
    {
        $command = 'echo ' . config( 'laravel-deploy.user.password' );
        $command .= ' | sudo -S -u ' . config( 'laravel-deploy.user.username' );
        $command .= ' sh ' . $this->script_file;

        $this->command = $command;

        $process = new Process( $command );
        $process->setTimeout( 500 );
        $process->setIdleTimeout( 100 );

        $this->process = $process;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event( new LaravelDeployStarted( $this->client, $this->command ) );

        $this->process->run();

        if ( $this->process->isSuccessful() ) {

            $this->logger->info( PHP_EOL . $this->process->getOutput(), [
                'client' => json_encode( $this->client ),
                'script' => $this->script_file,
            ] );
            event( new LaravelDeployFinished( $this->client, $this->process->getOutput() ) );

        } else {

            $this->logger->critical( PHP_EOL . $this->process->getOutput(), [
                'client' => json_encode( $this->client ),
                'script' => $this->script_file,
            ] );
            event( new LaravelDeployFailed( $this->client, $this->process->getOutput() ) );
        }

    }
}

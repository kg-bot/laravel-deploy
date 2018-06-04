<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use KgBot\LaravelDeploy\Events\LaravelDeployFinished;
use KgBot\LaravelDeploy\Events\LaravelDeployStarted;
use KgBot\LaravelDeploy\Models\DeploySource;
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
    public function __construct( DeploySource $client, string $script_file )
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
        $process = new Process( 'sh ' . $this->script_file );
        event( new LaravelDeployStarted( $this->client ) );
        $process->start();
        event( new LaravelDeployFinished( $this->client ) );
    }
}

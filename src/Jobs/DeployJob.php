<?php

namespace KgBot\LaravelDeploy\Jobs;

use Monolog\Logger;
use Illuminate\Bus\Queueable;
use Monolog\Handler\StreamHandler;
use Illuminate\Queue\SerializesModels;
use KgBot\LaravelDeploy\Models\Client;
use Symfony\Component\Process\Process;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use KgBot\LaravelDeploy\Events\LaravelDeployFailed;
use KgBot\LaravelDeploy\Events\LaravelDeployStarted;
use KgBot\LaravelDeploy\Events\LaravelDeployFinished;

class DeployJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 240;
    public $tries = 1;

    public $client;
    public $script_file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Client $client, string $script_file)
    {
        $this->client = $client;
        $this->script_file = $script_file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logger = new Logger('laravel-deploy-logger');

        $log_file_name = config('laravel-deploy.log_file_name', 'laravel-log');
        $logger->pushHandler(new StreamHandler(storage_path('/logs/'.$log_file_name), Logger::DEBUG));

        $command = 'echo '.config('laravel-deploy.user.password');
        $command .= ' | sudo -S -u '.config('laravel-deploy.user.username');
        $command .= ' sh '.$this->script_file;

        $process = new Process($command);
        event(new LaravelDeployStarted($this->client, $command));
        $process->run();
        try {
            $logger->info($process->getOutput(), [
                'client' => json_encode($this->client),
                'script' => $this->script_file,
            ]);
            event(new LaravelDeployFinished($this->client, $process->getOutput()));
        } catch (\Exception $exception) {
            $logger->critical($process->getOutput(), [
                'client' => json_encode($this->client),
                'script' => $this->script_file,
            ]);
            event(new LaravelDeployFailed($this->client, $exception->getMessage()));
        }
    }
}

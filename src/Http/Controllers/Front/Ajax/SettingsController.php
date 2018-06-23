<?php

namespace KgBot\LaravelDeploy\Http\Controllers\Front\Ajax;

use KgBot\LaravelDeploy\Models\Client;
use KgBot\LaravelDeploy\Jobs\DeployJob;
use KgBot\LaravelDeploy\Utils\LogParser;
use KgBot\LaravelDeploy\Http\Controllers\BaseController;

class SettingsController extends BaseController
{
    /**
     * Return last deploy log.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lastLog()
    {
        $reader = $this->getLogs();

        if (is_null($reader)) {
            return response()->json('Log file path does not exist, check your configuration and try again.', 404);
        }

        if (count($reader)) {
            $log = $reader[0];

            return response()->json(['log' => $log]);
        } else {
            return response()->json('Log is empty, deploy something!!!', 404);
        }
    }

    /**
     * Read deployment log file and return it's content.
     *
     * @return array|\KgBot\LaravelDeploy\Utils\LogParser|null
     * @throws \Exception
     */
    protected function getLogs()
    {
        $log_file_name = config('laravel-deploy.log_file_name', 'laravel-log');
        $path = storage_path('logs/'.$log_file_name);

        if (file_exists($path)) {
            $file = file_get_contents($path);
            if ($file) {
                $reader = new LogParser();
                $reader = $reader->parse($file);

                return $reader;
            } else {
                throw new \Exception('Couldn\'t read deployment log file.');
            }
        } else {
            return;
        }
    }

    /**
     * Return collection of all deploy logs.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function allLogs()
    {
        $reader = $this->getLogs();

        if (is_null($reader)) {
            return response()->json('Log file path does not exist, check your configuration and try again.', 404);
        }

        if (count($reader)) {
            return response()->json(['logs' => collect($reader)]);
        } else {
            return response()->json('Log is empty, deploy something!!!', 404);
        }
    }

    /**
     * Start deployment from web dashboard.
     *
     * @param \KgBot\LaravelDeploy\Models\Client $client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deployNow(Client $client)
    {
        dispatch(new DeployJob($client,
            base_path($client->script_source)))->onQueue(config('laravel-deploy.queue'));

        return response()->json('success');
    }

    /**
     * Open settings page of web dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /**
         * @var \Illuminate\Support\Collection
         */
        $clients = Client::Active()->get();

        $clients = $clients->each(function ($client) {
            $enabled = ($client->active) ? 'Enabled' : 'Disabled';

            return $client->text = $client->name.' - '.$enabled;
        });

        $settings = [

            'quick_deploy' => config('laravel-deploy.run_deploy'),
        ];

        return response()->json(compact('clients', 'settings'));
    }
}

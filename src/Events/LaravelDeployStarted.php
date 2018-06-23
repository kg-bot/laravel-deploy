<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 1:18 AM.
 */

namespace KgBot\LaravelDeploy\Events;

use Illuminate\Queue\SerializesModels;
use KgBot\LaravelDeploy\Models\Client;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class LaravelDeployStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $client;
    public $command;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Client $client, string $command)
    {
        $this->client = $client;
        $this->command = $command;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel(config('laravel-deploy.events.channel', 'channel-name'));
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 1:18 AM
 */

namespace KgBot\LaravelDeploy\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use KgBot\LaravelDeploy\Models\DeploySource;

class LaravelDeployStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $client;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( DeploySource $client )
    {
        $this->client = $client;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel( config( 'laravel-deploy.events.channel', 'channel-name' ) );
    }
}
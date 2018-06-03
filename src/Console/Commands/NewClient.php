<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 1:29 AM
 */

namespace KgBot\LaravelDeploy\Console\Commands;


use Illuminate\Console\Command;
use KgBot\LaravelDeploy\Models\DeploySource;

class NewClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-deploy:new-client {name} {token} {script_source} {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new deploy client';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $name          = $this->argument( 'name' );
        $token         = bcrypt( $this->argument( 'token' ) );
        $script_source = $this->argument( 'script_source' );
        $source        = $this->argument( 'source' );

        $client = DeploySource::create( compact( 'name', 'token', 'script_source', 'source' ) );

        $this->info( 'New client created, token has been encrypted and it is: ' . $token );
    }
}
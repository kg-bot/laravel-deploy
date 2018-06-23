<?php

namespace KgBot\LaravelDeploy\Http\Controllers\Front\Ajax;

use KgBot\LaravelDeploy\Models\Client;
use KgBot\LaravelDeploy\Http\Controllers\BaseController;
use KgBot\LaravelDeploy\Http\Requests\Clients\ClientRequest;

class ClientsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all();

        return response()->json(compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request)
    {
        $client = Client::create($request->all());

        return response()->json(compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request                     $request
     * @param  \App\KgBot\LaravelDeploy\Models\DeploySource $deploySource
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->all());

        return response()->json(compact('client'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KgBot\LaravelDeploy\Models\DeploySource $deploySource
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json('success');
    }

    public function changeStatus(Client $client)
    {
        $client->changeStatus();

        return response()->json('success');
    }

    public function changeAutoDeploy(Client $client)
    {
        $client->changeAutoDeploy();

        return response()->json(compact($client));
    }
}

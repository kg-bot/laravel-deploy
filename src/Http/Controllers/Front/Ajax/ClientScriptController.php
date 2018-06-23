<?php

namespace KgBot\LaravelDeploy\Http\Controllers\Front\Ajax;

use Illuminate\Http\Request;
use KgBot\LaravelDeploy\Models\Client;
use League\Flysystem\FileNotFoundException;
use KgBot\LaravelDeploy\Http\Controllers\BaseController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ClientScriptController extends BaseController
{
    public function fetch(Client $client)
    {
        $filepath = base_path(DIRECTORY_SEPARATOR.$client->script_source);

        if (file_exists($filepath)) {
            $content = file_get_contents($filepath);

            return response()->json(compact('content'));
        } else {
            throw new FileNotFoundException('We can\'t find deploy script defined for this client');
        }
    }

    public function save(Client $client, Request $request)
    {
        if ($request->has('content') && $content = $request->get('content')) {
            $filepath = base_path(DIRECTORY_SEPARATOR.$client->script_source);

            if (file_exists($filepath)) {
                file_put_contents($filepath, $content);

                return response()->json('success');
            } else {
                throw new FileNotFoundException('We can\'t find deploy script defined for this client');
            }
        } else {
            throw new BadRequestHttpException('Parameter content is required.');
        }
    }
}

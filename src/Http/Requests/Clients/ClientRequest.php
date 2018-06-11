<?php

namespace KgBot\LaravelDeploy\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'name'          => 'required|string',
            'source'        => 'required|string',
            'script_source' => 'required|string',
            'token'         => 'required|string',
            'auto_deploy'   => 'required|boolean',
        ];
    }
}

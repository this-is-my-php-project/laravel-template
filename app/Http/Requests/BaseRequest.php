<?php

namespace App\Http\Requests;

use App\Exceptions\Code;
use App\Exceptions\Message;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * @param $validator
     * @return void
     */
    public function withValidator($validator)
    {
        if ($validator->fails()) {
            throw new HttpResponseException((new Controller())->sendError(
                Message::FAILED,
                $validator->errors()->toArray(),
                Code::FAILED,
            ));
        }
    }
}

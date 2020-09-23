<?php

namespace DiaoJinLong\LaravelVueAdmin\Requests;

use DiaoJinLong\LaravelVueAdmin\Controllers\BaseController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class BaseRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = (new BaseController())->error($errors->first());
        throw (new HttpResponseException($response));
    }

}

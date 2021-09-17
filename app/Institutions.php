<?php

namespace App;

use App\Exceptions\ApiErrorCustom;
use App\Exceptions\ApiErrorException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Institutions extends Model
{

    /**
     * @param Request $request
     * @param array $rules
     * @throws ApiErrorCustom
     */
    public static function validate(Request $request, array $rules)
    {
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            throw new ApiErrorCustom('Erro na validação.', 400, null, $validator->errors()->toArray());
        }
    }
}

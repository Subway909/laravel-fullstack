<?php

namespace App\Http\Controllers;

use Canducci\ZipCode\Facades\ZipCode;
use Illuminate\Http\Request;

class CepController extends Controller
{
    public function getCep(Request $request) {
        $zipCodeInfo = ZipCode::find($request->cep);

        return $zipCodeInfo->getArray();
    }
}

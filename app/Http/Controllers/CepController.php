<?php

namespace App\Http\Controllers;

use Canducci\ZipCode\Facades\ZipCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CepController extends Controller
{
    public function getCep(Request $request) {
        //integração com o viacep para buscar ceps
        try {
            $zipCodeInfo = ZipCode::find($request->cep);

            if (!$zipCodeInfo) {
                throw new \Exception('Não foi possível buscar o CEP');
            }

            return $zipCodeInfo->getArray();
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}

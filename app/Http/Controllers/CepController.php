<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CepController extends Controller
{
    public function getCep(Request $request) {
        try {
            $url = VIACEP_URL.$request->cep."/json/";
            $zipCodeInfo = Http::get($url);

            if ($zipCodeInfo) {
                return $zipCodeInfo->json();
            }

            throw new \Exception('Unable to locate zipcode');
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}

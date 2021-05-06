<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use phpseclib3\File\X509;

class SecController extends Controller
{
    /**
     * @param $cert
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function readCertificate(Request $request)
    {

        $cert = $request;

        $dados = [];

        $dados["filename"] = self::storeCert($cert);

        Log::info("Lendo arquivo: ".$dados["filename"]);

        try {
            $x509 = new X509();
            $cert = $x509->loadX509(File::get($cert->arquivo->getRealPath()));

            $dados['dn']        = $x509->getDN(X509::DN_STRING);
            $dados['issuer_dn'] = $x509->getIssuerDN(X509::DN_STRING);

            $dados['validityNotBefore'] = $cert["tbsCertificate"]["validity"]["notBefore"]["utcTime"];
            $dados['validityNotAfter']  = $cert["tbsCertificate"]["validity"]["notAfter"]["utcTime"];

            Log::info(print_r($dados, true));
        } catch (\Exception $e) {
            Log::error('Erro ao ler o certificado: ' . $e->getMessage());
        }

        return $dados;
    }

    private function storeCert($cert)
    {
        $filename = $cert->arquivo->getClientOriginalName();
        try {
            Storage::disk('local')->put('/certificados/' . $filename, file_get_contents($cert->arquivo));
        } catch (\Exception $e) {
            Log::error('Não foi possível salvar o arquivo: ' . $e->getMessage());
        }


        return $filename;
    }
}

<?php

/* Teste completo da API:
     - Faz login
     - Pega o token para ser usado nas requisições
     - Cadastra um usuário
     - Consulta o usuário que foi cadastrado
     - Edita o usuário que foi cadastrado
     - Busca o usuário que foi cadastrado
     - Deleta o usuário que foi cadastrado
*/

namespace Tests\Unit;

use App\Http\Controllers\SecController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CertificadoTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    private $file;

    public function setUp(): void
    {
        parent::setUp();

        $this->file = __DIR__.'/teste.pem';
    }

    /**
     * testa o delete de usuário
     */
    public function testLerCertificado()
    {
        $request = app()->make(Request::class);

        $request->arquivo = new \Illuminate\Http\UploadedFile($this->file, 'teste.pem', null, null, true);
        $cert = new SecController();
        $dadosCert = $cert->readCertificate($request);

        $this->assertEquals('teste.pem', $dadosCert["filename"]);
        $this->assertEquals('C=BR, O=ICP-Brasil, OU=Certificado PF A1, OU=AC SOLUTI Multipla, OU=AC SOLUTI, CN=TESTE UPPERCASE:01234567890', $dadosCert["dn"]);
        $this->assertEquals('C=BR, O=ICP-Brasil, OU=AC RAIZ teste v2, OU=AC SOLUTI teste v2, CN=AC SOLUTI Multipla teste v2', $dadosCert["issuer_dn"]);
        $this->assertEquals('Mon, 04 Apr 2016 21:32:59 +0000', $dadosCert["validityNotBefore"]);
        $this->assertEquals('Tue, 04 Apr 2017 21:32:59 +0000', $dadosCert["validityNotAfter"]);
    }
}

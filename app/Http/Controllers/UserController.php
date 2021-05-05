<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Telefone;
use App\Rules\ChecaCpf;
use App\Rules\ChecaMascaraCpf;
use App\Rules\ChecaNomeCompleto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User;
use \App\Models\User as Usuario;
use Illuminate\Support\Facades\Storage;
use phpseclib3\File\X509;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Lista todos os usuário
     *
     * @return \Illuminate\Database\Eloquent\Collection|User[]
     */
    public function index()
    {
        $users = Usuario::with(['enderecos', 'telefones'])->get();

        return $users;
    }

    /**
     * Insere novo usuário
     *
     * @param \Illuminate\Http\Request $request
     * @return User
     */
    public function store(Request $request)
    {

        if ($request->arquivo) {

            $filename = $request->arquivo->getClientOriginalName();

            Storage::disk('local')->put('/certificados/'.$filename, file_get_contents($request->arquivo));

            $x509 = new X509();
            $cert = $x509->loadX509(File::get($request->arquivo->getRealPath()));

            $dn = $x509->getDN(X509::DN_STRING);
            $issuer_dn = $x509->getIssuerDN(X509::DN_STRING);

            $validityNotBefore = $cert["tbsCertificate"]["validity"]["notBefore"]["utcTime"];
            $validityNotAfter = $cert["tbsCertificate"]["validity"]["notAfter"]["utcTime"];
        }

        #validação dos campos
        $this->validaUsuario($request, true);

        $user = new Usuario();

        $user->email                 = $request->email;
        $user->name                  = $request->name;
        $user->cpf                   = $request->cpf;
        $user->data_nascimento       = $request->data_nascimento;
        $user->password              = Hash::make($request->password);

        $carbon = new Carbon();

        if (isset($cert)) {
            $user->certificado = $filename;
            $user->certificado_dn = $dn;
            $user->certificado_issuer_dn = $issuer_dn;
            $user->certificado_not_before = $carbon::parse($validityNotBefore)->toDateTimeString();
            $user->certificado_not_after = $carbon::parse($validityNotAfter)->toDateTimeString();
        }

        $user->save();

        if ($request->enderecos) {
            if (!is_array($request->enderecos)) {
                $endereco[0] = json_decode($request->enderecos, true);
                $request->enderecos = $endereco;
            }

            $user->enderecos()->createMany($request->enderecos);
        }

        if ($request->telefones) {
            if (!is_array($request->telefones)) {
                $telefone[0] = json_decode($request->telefones, true);
                $request->telefones = $telefone;
            }

            $user->telefones()->createMany($request->telefones);
        }

        return $user;
    }

    /**
     * Busca usuário por id
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        #valida se o id existe no banco
        $user = Usuario::with(['enderecos', 'telefones'])->where('id', $id)->get();

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        return $user;
    }

    /**
     * Validação de nome completo, email e cpf, com regras custom
     * @param $request
     * @param $required
     * @param string $id
     */
    public function validaUsuario($request, $required, $id = "")
    {
        $req = $required ? "required" : "";

        $request->validate([
            'email' => "$req |email:rfc|unique:users,email,$id",
            'cpf' => "$req |unique:users,cpf,$id"
        ]);

        if (!empty($request->cpf)) {
            $request->validate(["cpf" => new ChecaCpf()]);
            $request->validate(["cpf" => new ChecaMascaraCpf()]);
        }

        if (!empty($request->name)) {
            $request->validate(["name" => new ChecaNomeCompleto()]);
        }
    }

    /**
     * Edição de usuário
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Usuario|Usuario[]|\Illuminate\Database\Eloquent\Collection|Model|\Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {

        $this->validaUsuario($request, false, $id);

        $user = Usuario::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        if (!empty($request->email))
            $user->email = $request->email;

        if (!empty($request->name))
            $user->name = $request->name;

        if (!empty($request->cpf))
            $user->cpf = $request->cpf;

        if (!empty($request->password))
            $user->password = Hash::make($request->password);

        $user->update();

        if($request->enderecos) {
            foreach($request->enderecos as $end) {
                $endereco = Endereco::find($end["id"]);
                $endereco->update($end);
            }
        }

        if($request->telefones) {
            foreach($request->telefones as $tel) {
                $telefone = Telefone::find($tel["id"]);
                $telefone->update($tel);
            }
        }

        $user = Usuario::with(['enderecos', 'telefones'])->where('id', $id)->get()->toArray();
        unset($user["password"]);

        return $user;
    }

    /**
     * Delete de usuário
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

        $user_logado = auth()->user();

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        if ($user_logado->id == $user->id) {
            return response()->json(['error' => 'Não é permitido deletar o usuário que está logado'], 200);
        }

        $user->delete();

        return $user;
    }
}

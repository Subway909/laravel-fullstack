<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Telefone;
use App\Rules\ChecaCpf;
use App\Rules\ChecaMascaraCpf;
use App\Rules\ChecaNomeCompleto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User;
use \App\Models\User as Usuario;
use Throwable;

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

        #validação dos campos
        $this->validaUsuario($request, true);

        $user = new Usuario();

        $user->email                 = $request->email;
        $user->name                  = $request->name;
        $user->cpf                   = $request->cpf;
        $user->data_nascimento       = $request->data_nascimento;
        $user->certificado           = $request->certificado;
        $user->certificado_validade  = $request->certificado_validade;
        $user->certificado_dn        = $request->certificado_dn;
        $user->certificado_issuer_dn = $request->certificado_issuer_dn;
        $user->password              = Hash::make($request->password);

        $user->save();

        if ($request->enderecos) {
            $user->enderecos()->createMany($request->enderecos);
        }

        if ($request->telefones) {
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

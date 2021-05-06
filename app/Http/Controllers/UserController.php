<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\Telefone;
use App\Rules\ChecaCpf;
use App\Rules\ChecaMascaraCpf;
use App\Rules\ChecaNomeCompleto;
use Carbon\Traits\Creator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User;
use \App\Models\User as Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|User|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $existeUser = self::existeUser($request);

        if ($existeUser) {
            return response(['error' => $existeUser]);
        }

        #validação dos campos
        $this->validaUsuario($request, true);

        $user = new Usuario();

        $user->email           = $request->email;
        $user->name            = $request->name;
        $user->cpf             = $request->cpf;
        $user->data_nascimento = $request->data_nascimento;
        $user->password        = Hash::make($request->password);

        if ($request->arquivo) {
            $cert      = new SecController();
            $dadosCert = $cert->readCertificate($request);

            if (isset($dadosCert)) {
                $carbon = new Carbon();

                $user->certificado            = $dadosCert['filename'];
                $user->certificado_dn         = $dadosCert['dn'];
                $user->certificado_issuer_dn  = $dadosCert['issuer_dn'];
                $user->certificado_not_before = $carbon::parse($dadosCert['validityNotBefore'])->toDateTimeString();
                $user->certificado_not_after  = $carbon::parse($dadosCert['validityNotAfter'])->toDateTimeString();
            }
        }

        DB::transaction(function () use ($user, $request) {
            $user->save();

            if ($request->enderecos) {
                if (!is_array($request->enderecos)) {
                    $endereco        = json_decode($request->enderecos, true);
                    $request->enderecos = $endereco;
                }

                $user->enderecos()->create($request->enderecos);
            }

            if ($request->telefones) {
                if (!is_array($request->telefones)) {
                    $telefone       = json_decode($request->telefones, true);
                    $request->telefones = $telefone;
                }

                $user->telefones()->create($request->telefones);
            }
        });

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

        if ($request->arquivo) {
            $cert      = new SecController();
            $dadosCert = $cert->readCertificate($request);

            if (isset($dadosCert)) {
                $carbon = new Carbon();

                $user->certificado            = $dadosCert['filename'];
                $user->certificado_dn         = $dadosCert['dn'];
                $user->certificado_issuer_dn  = $dadosCert['issuer_dn'];
                $user->certificado_not_before = $carbon::parse($dadosCert['validityNotBefore'])->toDateTimeString();
                $user->certificado_not_after  = $carbon::parse($dadosCert['validityNotAfter'])->toDateTimeString();
            }
        }

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


        DB::transaction(function () use ($user, $request) {
            $user->update();

            if ($request->enderecos) {

                if (!is_array($request->enderecos)) {
                    $endereco           = json_decode($request->enderecos, true);
                    $request->enderecos = $endereco;
                }

                $enderecoModel = Endereco::find($request->enderecos["id"]);

                if ($enderecoModel) {
                    $enderecoModel->update($request->enderecos);
                }
                else {
                    $user->enderecos()->create($request->enderecos);
                }
            }

            if ($request->telefones) {
                if (!is_array($request->telefones)) {
                    $telefones          = json_decode($request->telefones, true);
                    $request->telefones = $telefones;
                }

                $telefoneModel = Telefone::find($request->telefones["id"]);

                if ($telefoneModel) {
                    $telefoneModel->update($request->telefones);
                }
                else {
                    $user->telefones()->create($request->telefones);
                }
            }
        });

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

    public function existeUser($dados)
    {
        $msg  = '';
        $user = Usuario::whereEmail($dados->email)->first();

        if ($user) {
            $msg = 'Usuário já cadastrado com esse email.';
        } else {
            $user = Usuario::whereCpf($dados->cpf)->first();

            if ($user) {
                $msg = 'Usuário já cadastrado com esse CPF';
            }
        }


        return $msg;
    }
}

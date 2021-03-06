<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Efetua o login e retorna um token JWT
     * @param Request $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $dados_user = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($dados_user)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        else {
            $user = Auth::user();
            $user->qtd_acessos = $user->qtd_acessos + 1;

            $user->save();

            Log::debug("Login realizado: ".$user->email);
        }

        return $this->respondWithToken($token, $user);
    }

    /**
     * Retorna o token em array.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }

    /**
     * Retorna o usuário logado.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Logout - invalida o token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Logout efetuado com sucesso']);
    }
}

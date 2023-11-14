<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class Authentication extends BaseController
{
    /**
     * Autentica o usuario criando um token de acesso no banco
     *
     * @param LoginRequest $request
     * @return JsonResponse|Response
     */
    public function login(LoginRequest $request)#: JsonResponse|Response
    {
        if (! Auth::attempt($request->only(['email', 'password']))) {
            return $this->sendError('Credenciais inválidas! Verifique e tente novamente.', code: 400);
        }

        $user = User::where('email', '=', $request->email)->first();

        if (! empty($user->tokens)) {
            $user->tokens()->delete();
        }

        $authentication_token = $user->createToken(
            $user->email,
            expiresAt: new DateTimeImmutable('+ 1 hour')
        )->plainTextToken;

        return $this->sendResponse('Bem-vindo(a) ' . $user->name, response_data: ['bearer' => $authentication_token]);
    }

    public function loginError()
    {
        return response()->json([
            'success' => false,
            'status'  => 'error',
            'message' => 'Realize login para acessar os recursos da aplicação'
        ], $unprocessable_content = 422);
    }

    /**
     * Remove o token de acesso do banco
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function logout(Request $request): JsonResponse|Response
    {
        $request->user()->tokens()->delete();

        return $this->sendResponse('Usuário desconectado!');
    }
}

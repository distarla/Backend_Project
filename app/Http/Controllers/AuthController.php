<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {

        //autenticação (email e password)
        $credentials=$request->all(['email','password']);

        $token=auth('api')->attempt($credentials);

        if ($token) {
            //user autenticado com sucesso
            return response()->json(['token'=>$token]);
        } else {
            return response()->json(['erro'=>'Email ou password incorretos!'],403);
        }

        //retornar um Json Web Token
        return 'login';
    }

    public function logout() {
        auth('api')->logout();
        return response()->json(["msg"=>"Logout realizado com sucesso"]);
    }

    public function refresh() {
        $token=auth('api')->refresh();
        //cliente tem de encaminhar um token válido
        return response()->json(["token"=>$token]);
    }

    public function me() {
        return response()->json(auth()->user());
    }
}

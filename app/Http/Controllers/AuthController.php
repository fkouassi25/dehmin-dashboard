<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm(Request $req) {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = ['email'=> $request->email, 'password'=> $request->pwd];

        if (Auth::attempt($credentials)) {
            return redirect('/list_benef');
        }

        return redirect('/login');
    }

    public function logout(Request $req) {
        Auth::logout();
        return redirect('/login');
    }

    public function signUpForm(Request $req) {
        return view('auth.signup');
    }

    public function signup(Request $req) {
        $user = new User;
        try {
            $user->nom = isset($req->nom) ? $req->nom : '';
            $user->prenom = isset($req->prenom) ? $req->prenom : '';
            $user->email = isset($req->email) ? $req->email : '';
            $user->password = isset($req->password) ? bcrypt($req->password) : '';

            $user->save();
            return redirect('/login');

            // $resp['code'] = 'success';
            // return $resp;
        } catch (\Throwable $th) {
            dd($th);
            $resp['code'] = 'err';
            $resp['msg'] = "Oups! Une erreur s'est produite lors de l'enregistrement en base de données.";
            return $resp;
        }
    }
}

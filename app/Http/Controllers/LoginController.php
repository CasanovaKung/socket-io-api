<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use App\User;
use Auth;

class LoginController extends Controller
{
    public function postLogin(Request $request)
    {
        $arrData = $request->only('email', 'password');
        $this->validateLogin($request);
        $arrJsonResponse['rs'] = 0;
        if (Auth::attempt($arrData))
        {
            $objUser = User::where('email', $arrData['email'])->first();
            $objUser->api_token = str_random(60);
            $objUser->save();
            $arrJsonResponse['rs'] = 1;
            $arrJsonResponse['user'] = $request->user();
        }
        else
        {
            $arrJsonResponse['msg'] = 'Username or password is invalid';
        }
        return response()->json($arrJsonResponse);
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }
}

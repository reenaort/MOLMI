<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;


class AuthController extends BaseController
{

    /**
     * Login
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {

            $request->validate(
                [
                    'email' => 'required',
                    'password' => 'required',
                ],
                [
                    'email.required' => "Please enter email.",
                    'password.required' => "Please enter password.",
                ]
            );
            $creds = array('email' => $request->email, 'password' => ($request->password));
            if (Auth::guard('web')->attempt($creds)) {
                $user = Auth::getProvider()->retrieveByCredentials($creds);
                session()->put('role', 'admin');
                session()->put('apitoken',  $user->createToken('LMS_Matrix')->plainTextToken);
                session()->put('user_id', $user->id);
                session()->put('user_name', $user->name);
                return $this->sendResponse([], 'User login successfully.');
            } else {
                return $this->sendError([], 'Invalid credentials.');
            }
        } catch (ValidationException $e) {
            return $this->sendError('error', ['error' => $e->errors()]);
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }
}

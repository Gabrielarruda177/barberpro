<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class PasswordResetController extends Controller
{
    /**
     * Mostrar formulário de "esqueci a senha"
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar link de recuperação
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link de recuperação enviado para seu email!');
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    /**
     * Mostrar formulário de redefinição de senha
     */
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', ['token' => $request->route('token'), 'email' => $request->email]);
    }

    /**
     * Processar redefinição de senha
     */
    public function update(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Senha redefinida com sucesso! Agora você pode fazer login.');
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}

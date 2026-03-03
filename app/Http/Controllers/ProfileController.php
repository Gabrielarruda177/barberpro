<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'senha_atual' => 'nullable|string',
            'nova_senha'  => 'nullable|string|min:8|confirmed',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        // Alterar senha
        if ($request->filled('senha_atual') && $request->filled('nova_senha')) {
            if (!Hash::check($request->senha_atual, $user->password)) {
                return back()->withInput()
                             ->withErrors(['senha_atual' => 'A senha atual está incorreta.']);
            }
            $user->password = Hash::make($request->nova_senha);
        }

        // Upload foto — salva em public/fotos/ (sem necessidade de symlink)
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {

            // Remove foto anterior do disco
            if (!empty($user->foto)) {
                $old = public_path('fotos/' . $user->foto);
                if (file_exists($old)) {
                    @unlink($old);
                }
            }

            $ext      = $request->file('foto')->getClientOriginalExtension();
            $filename = 'user_' . $user->id . '_' . time() . '.' . $ext;
            $destDir  = public_path('fotos');

            // Cria o diretório se não existir
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }

            // Move para public/fotos/ — acessível via asset('fotos/arquivo.jpg')
            $request->file('foto')->move($destDir, $filename);
            $user->foto = $filename;
        }

        $user->save();

        return redirect()->route('profile.index')
                         ->with('success', 'Perfil atualizado com sucesso!');
    }

    public function removePhoto()
    {
        $user = Auth::user();

        if (!empty($user->foto)) {
            $path = public_path('fotos/' . $user->foto);
            if (file_exists($path)) {
                @unlink($path);
            }
            $user->foto = null;
            $user->save();
        }

        return redirect()->route('profile.index')
                         ->with('success', 'Foto removida com sucesso!');
    }
}
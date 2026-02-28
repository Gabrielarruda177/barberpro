<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Mostrar página de perfil
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Atualizar perfil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'senha_atual' => 'nullable|string',
            'nova_senha' => 'nullable|string|min:8|confirmed',
        ]);

        // Atualizar nome e email
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Atualizar senha se fornecida
        if ($request->senha_atual && $request->nova_senha) {
            if (!Hash::check($request->senha_atual, $user->password)) {
                return back()->withErrors(['senha_atual' => 'A senha atual está incorreta.']);
            }
            $user->password = Hash::make($request->nova_senha);
            $user->save();
        }

        // Upload de foto
        if ($request->hasFile('foto')) {
            // Remover foto anterior se existir
            if ($user->foto && Storage::disk('public')->exists('fotos/' . $user->foto)) {
                Storage::disk('public')->delete('fotos/' . $user->foto);
            }

            // Salvar nova foto
            $filename = $user->id . '_' . time() . '.' . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->storeAs('fotos', $filename, 'public');
            $user->foto = $filename;
            $user->save();
        }

        return redirect()->route('profile.index')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Remover foto de perfil
     */
    public function removePhoto()
    {
        $user = Auth::user();

        if ($user->foto && Storage::disk('public')->exists('fotos/' . $user->foto)) {
            Storage::disk('public')->delete('fotos/' . $user->foto);
            $user->foto = null;
            $user->save();
        }

        return redirect()->route('profile.index')->with('success', 'Foto removida com sucesso!');
    }
}

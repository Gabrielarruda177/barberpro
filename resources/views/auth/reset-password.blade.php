@extends('layouts.auth')

@section('title', 'Nova Senha')

@section('content')

<div class="form-eyebrow">Redefinição de senha</div>
<h1 class="form-title">Criar nova senha</h1>
<p class="form-sub">Escolha uma senha forte para proteger sua conta</p>

<form method="POST" action="{{ route('password.update') }}" id="resetForm">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

    <div class="field">
        <label for="password" class="field-label">Nova Senha</label>
        <div class="input-shell">
            <i class="fas fa-lock icon-left"></i>
            <input type="password" id="password" name="password"
                   placeholder="Mínimo 8 caracteres"
                   autocomplete="new-password"
                   class="{{ $errors->has('password') ? 'has-error' : '' }}"
                   required autofocus>
            <button type="button" class="toggle-pw" onclick="togglePassword('password')">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        @error('password')
            <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
        @enderror
    </div>

    <div class="field">
        <label for="password_confirmation" class="field-label">Confirmar Nova Senha</label>
        <div class="input-shell">
            <i class="fas fa-lock icon-left"></i>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   placeholder="Repita a nova senha"
                   autocomplete="new-password"
                   required>
            <button type="button" class="toggle-pw" onclick="togglePassword('password_confirmation')">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="btn-submit" id="submitBtn">
        <i class="fas fa-shield-halved"></i>
        Salvar nova senha
    </button>
</form>

<div class="form-divider"><span>ou</span></div>

<div class="form-footer">
    Lembrou a senha? <a href="{{ route('login') }}">Fazer login</a>
</div>

@push('scripts')
<script>
document.getElementById('resetForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
});
</script>
@endpush

@endsection
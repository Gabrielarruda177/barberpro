@extends('layouts.auth')

@section('title', 'Entrar')

@section('content')

<div class="form-eyebrow">Bem-vindo de volta</div>
<h1 class="form-title">Acesse sua conta</h1>
<p class="form-sub">Digite suas credenciais para continuar</p>

<form method="POST" action="{{ route('login') }}" id="loginForm">
    @csrf

    <div class="field">
        <label for="email" class="field-label">E-mail</label>
        <div class="input-shell">
            <i class="fas fa-envelope icon-left"></i>
            <input type="email" id="email" name="email"
                   placeholder="seu@email.com"
                   value="{{ old('email') }}"
                   autocomplete="email"
                   class="{{ $errors->has('email') ? 'has-error' : '' }}"
                   required autofocus>
        </div>
        @error('email')
            <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
        @enderror
    </div>

    <div class="field">
        <label for="password" class="field-label">Senha</label>
        <div class="input-shell">
            <i class="fas fa-lock icon-left"></i>
            <input type="password" id="password" name="password"
                   placeholder="••••••••"
                   autocomplete="current-password"
                   class="{{ $errors->has('password') ? 'has-error' : '' }}"
                   required>
            <button type="button" class="toggle-pw" onclick="togglePassword('password')">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        @error('password')
            <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
        @enderror
    </div>

    <div class="form-extras">
        <label class="check-label">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Lembrar-me
        </label>
        @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-link">Esqueceu a senha?</a>
        @endif
    </div>

    <button type="submit" class="btn-submit" id="submitBtn">
        <i class="fas fa-arrow-right-to-bracket"></i>
        Entrar
    </button>
</form>

<div class="form-divider"><span>ou</span></div>

<div class="form-footer">
    Não tem uma conta?
    @if(Route::has('register'))
        <a href="{{ route('register') }}">Criar conta</a>
    @endif
</div>

@push('scripts')
<script>
document.getElementById('loginForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Entrando...';
});
</script>
@endpush

@endsection
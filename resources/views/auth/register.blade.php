@extends('layouts.auth')

@section('title', 'Criar Conta')

@section('content')

<div class="form-eyebrow">Novo por aqui?</div>
<h1 class="form-title">Criar conta</h1>
<p class="form-sub">Preencha os dados para se juntar ao BarberPro</p>

<form method="POST" action="{{ route('register') }}" id="registerForm">
    @csrf

    <div class="field">
        <label for="name" class="field-label">Nome Completo</label>
        <div class="input-shell">
            <i class="fas fa-user icon-left"></i>
            <input type="text" id="name" name="name"
                   placeholder="Seu nome completo"
                   value="{{ old('name') }}"
                   autocomplete="name"
                   class="{{ $errors->has('name') ? 'has-error' : '' }}"
                   required autofocus>
        </div>
        @error('name')
            <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
        @enderror
    </div>

    <div class="field">
        <label for="email" class="field-label">E-mail</label>
        <div class="input-shell">
            <i class="fas fa-envelope icon-left"></i>
            <input type="email" id="email" name="email"
                   placeholder="seu@email.com"
                   value="{{ old('email') }}"
                   autocomplete="email"
                   class="{{ $errors->has('email') ? 'has-error' : '' }}"
                   required>
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
                   placeholder="Mínimo 8 caracteres"
                   autocomplete="new-password"
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

    <div class="field">
        <label for="password_confirmation" class="field-label">Confirmar Senha</label>
        <div class="input-shell">
            <i class="fas fa-lock icon-left"></i>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   placeholder="Repita a senha"
                   autocomplete="new-password"
                   required>
            <button type="button" class="toggle-pw" onclick="togglePassword('password_confirmation')">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>

    {{-- Termos --}}
    <div class="field" style="margin-bottom:1.4rem;">
        <label class="check-label" style="align-items:flex-start;gap:0.6rem;">
            <input type="checkbox" name="terms" id="terms" required style="margin-top:2px;flex-shrink:0;">
            <span style="font-size:0.82rem;color:var(--text-muted);line-height:1.5;">
                Eu aceito os
                <a href="#" style="color:var(--gold);font-weight:600;">Termos de Uso</a>
                e a
                <a href="#" style="color:var(--gold);font-weight:600;">Política de Privacidade</a>
            </span>
        </label>
    </div>

    <button type="submit" class="btn-submit" id="submitBtn">
        <i class="fas fa-user-plus"></i>
        Criar conta
    </button>
</form>

<div class="form-divider"><span>ou</span></div>

<div class="form-footer">
    Já tem uma conta? <a href="{{ route('login') }}">Fazer login</a>
</div>

@push('scripts')
<script>
document.getElementById('registerForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Criando conta...';
});
</script>
@endpush

@endsection
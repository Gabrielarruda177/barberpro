@extends('layouts.auth')

@section('title', 'Esqueci a Senha')

@section('content')

<div class="form-eyebrow">Recuperação de acesso</div>
<h1 class="form-title">Esqueceu a senha?</h1>
<p class="form-sub">Informe seu e-mail e enviaremos um link para redefinir sua senha</p>

<form method="POST" action="{{ route('password.email') }}" id="forgotForm">
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

    <button type="submit" class="btn-submit" id="submitBtn">
        <i class="fas fa-paper-plane"></i>
        Enviar link de recuperação
    </button>
</form>

<div class="form-divider"><span>ou</span></div>

<div class="form-footer">
    Lembrou a senha? <a href="{{ route('login') }}">Fazer login</a>
</div>

@push('scripts')
<script>
document.getElementById('forgotForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
});
</script>
@endpush

@endsection
@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('styles')
<style>
    :root {
        --dark: #1a1a1a;
        --dark-elevated: #242424;
        --dark-border: #333;
        --gold: #c9a84c;
        --gold-light: #d4b45f;
        --gold-dim: rgba(201, 168, 76, 0.2);
        --text-primary: #e0e0e0;
        --text-dim: #b0b0b0;
        --text-muted: #808080;
        --green: #4caf50;
        --red: #e53935;
    }

    body {
        background: var(--dark);
        color: var(--text-primary);
        font-family: 'DM Sans', sans-serif;
        margin: 0;
        padding: 0;
        line-height: 1.6;
    }

    .page-wrap {
        padding: 2rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--text-primary);
    }

    .page-title i {
        color: var(--gold);
    }

    .profile-card {
        background: var(--dark-elevated);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--dark-border);
    }

    .profile-header {
        background: linear-gradient(135deg, var(--dark-elevated), #2a2a2a);
        padding: 2.5rem 2rem;
        text-align: center;
        border-bottom: 1px solid var(--dark-border);
    }

    .avatar-container {
        position: relative;
        display: inline-block;
        margin-bottom: 1.25rem;
    }

    .avatar {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: var(--gold);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
        border: 3px solid var(--gold-dim);
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-initials {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--dark);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .avatar-edit-btn {
        position: absolute;
        bottom: 4px;
        right: 4px;
        background: var(--gold);
        color: var(--dark);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        transition: background 0.2s, transform 0.2s;
    }

    .avatar-edit-btn:hover {
        background: var(--gold-light);
        transform: scale(1.1);
    }

    .profile-name {
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: var(--text-primary);
    }

    .profile-email {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .profile-body {
        padding: 2rem;
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--gold);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--dark-border);
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-dim);
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        background: var(--dark-elevated);
        border: 1px solid var(--dark-border);
        color: var(--text-primary);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.95rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--gold-dim);
        box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.15);
    }

    .form-control::placeholder {
        color: var(--text-muted);
        opacity: 0.6;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    @media (max-width: 640px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .btn-gold {
        background: var(--gold);
        color: var(--dark);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background 0.2s, transform 0.15s;
    }

    .btn-gold:hover {
        background: var(--gold-light);
        transform: translateY(-1px);
    }

    .btn-ghost {
        background: transparent;
        color: var(--text-dim);
        border: 1px solid var(--dark-border);
        padding: 0.65rem 1.25rem;
        border-radius: 10px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 500;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s;
    }

    .btn-ghost:hover {
        border-color: var(--gold-dim);
        color: var(--gold);
    }

    .btn-danger {
        background: rgba(139, 51, 51, 0.15);
        color: var(--red);
        border: 1px solid rgba(139, 51, 51, 0.3);
        padding: 0.65rem 1.25rem;
        border-radius: 10px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s;
    }

    .btn-danger:hover {
        background: rgba(139, 51, 51, 0.25);
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--dark-border);
    }

    .form-actions-left {
        display: flex;
        gap: 0.75rem;
    }

    .toast {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 2000;
        background: var(--dark-elevated);
        border: 1px solid var(--dark-border);
        border-radius: 10px;
        padding: 0.9rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
        font-size: 0.84rem;
        color: var(--text-primary);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        transform: translateY(120%);
        transition: transform 0.3s cubic-bezier(0.34, 1.3, 0.64, 1);
        min-width: 280px;
    }

    .toast.show {
        transform: translateY(0);
    }

    .toast.success {
        border-left: 3px solid var(--green);
    }

    .toast.error {
        border-left: 3px solid var(--red);
    }

    .toast.success i {
        color: var(--green);
    }

    .toast.error i {
        color: var(--red);
    }

    .error-text {
        color: var(--red);
        font-size: 0.8rem;
        margin-top: 0.35rem;
    }

    .has-error {
        border-color: var(--red) !important;
    }
</style>
@endsection

@section('content')
<div class="page-wrap">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-user-circle"></i>
            Meu Perfil
        </h1>
    </div>

    <div class="profile-card">
        <div class="profile-header">
            <div class="avatar-container">
                <div class="avatar">
                    @php
                        $temFoto = !empty($user->foto) && file_exists(public_path('fotos/' . $user->foto));
                    @endphp
                    @if($temFoto)
                        <img src="{{ asset('fotos/' . $user->foto) }}" alt="{{ $user->name }}">
                    @else
                        <span class="avatar-initials">
                            {{ collect(explode(' ', $user->name))->filter(function($w) { return !empty($w); })->map(function($w) { return strtoupper(substr($w, 0, 1)); })->take(2)->implode('') }}
                        </span>
                    @endif
                </div>
                <label for="foto" class="avatar-edit-btn" title="Alterar foto">
                    <i class="fas fa-camera"></i>
                </label>
            </div>
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-email">{{ $user->email }}</div>
        </div>

        <div class="profile-body">
            <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="file" id="foto" name="foto" accept="image/*" style="display: none;">

                <div class="form-section">
                    <div class="section-title">Informações Pessoais</div>

                    <div class="form-group">
                        <label for="name">Nome Completo</label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">Alterar Senha</div>

                    <div class="form-group">
                        <label for="senha_atual">Senha Atual</label>
                        <input type="password" id="senha_atual" name="senha_atual" class="form-control" 
                               placeholder="Digite a senha atual (opcional)">
                        @error('senha_atual')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nova_senha">Nova Senha</label>
                            <input type="password" id="nova_senha" name="nova_senha" class="form-control" 
                                   placeholder="Mínimo 8 caracteres">
                            @error('nova_senha')
                                <div class="error-text">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nova_senha_confirmation">Confirmar Nova Senha</label>
                            <input type="password" id="nova_senha_confirmation" name="nova_senha_confirmation" 
                                   class="form-control" placeholder="Confirme a nova senha">
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <div class="form-actions-left">
                        @php
                            $temFoto = !empty($user->foto) && file_exists(public_path('fotos/' . $user->foto));
                        @endphp
                        @if($temFoto)
                            <button type="button" class="btn-danger" onclick="removePhoto()">
                                <i class="fas fa-trash"></i> Remover Foto
                            </button>
                        @endif
                    </div>
                    <button type="submit" class="btn-gold" id="submitBtn">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form para remover foto -->
<form id="removePhotoForm" action="{{ route('profile.remove-photo') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Toast -->
<div id="toast" class="toast">
    <i id="toastIcon" class="fas fa-check-circle"></i>
    <span id="toastMsg"></span>
</div>
@endsection

@push('scripts')
<script>
    // Preview da foto quando selecionar
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatar = document.querySelector('.avatar');
                avatar.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Remover foto
    function removePhoto() {
        if (confirm('Tem certeza que deseja remover sua foto de perfil?')) {
            document.getElementById('removePhotoForm').submit();
        }
    }

    // Feedback do form
    document.getElementById('profileForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    });

    // Toast
    function showToast(msg, type = 'success') {
        const t = document.getElementById('toast');
        const icon = document.getElementById('toastIcon');
        document.getElementById('toastMsg').textContent = msg;
        t.className = `toast ${type} show`;
        icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        setTimeout(() => t.classList.remove('show'), 3800);
    }

    @if(session('success'))
        showToast(@json(session('success')), 'success');
    @endif

    @if(session('error'))
        showToast(@json(session('error')), 'error');
    @endif

    @if($errors->any())
        showToast('Preencha os campos corretamente.', 'error');
    @endif
</script>
@endpush
@extends('layouts.auth')

@section('title', 'Criar Conta - Luvit')

@vite(['resources/js/pages/auth/register.js'])

@section('content')

<section class="register-section">
    <div class="register-visual">
        <div class="bg-shapes">
            <div class="shape">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="40" fill="url(#gradient1)"/>
                    <defs>
                        <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#e11d48"/>
                            <stop offset="100%" style="stop-color:#fb7185"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <div class="shape">
                <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="100" height="100" rx="25" fill="url(#gradient2)"/>
                    <defs>
                        <linearGradient id="gradient2" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#f43f5e"/>
                            <stop offset="100%" style="stop-color:#e11d48"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <div class="shape">
                 <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="30,5 55,55 5,55" fill="url(#gradient3)"/>
                    <defs>
                        <linearGradient id="gradient3" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#fb7185"/>
                            <stop offset="100%" style="stop-color:#f43f5e"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        </div>

        <div class="brand-visual">
            <div class="brand-logo-large">
                <svg width="40" height="40" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <h1 class="brand-title">Luvit</h1>
            <p class="brand-tagline">Crie sua conta e junte-se a nós!</p>
        </div>
    </div>

    <div class="register-form-side">
        <div class="register-container">
            <div class="brand-header">
                <h1 class="welcome-title">Crie sua conta!</h1>
                <p class="welcome-subtitle">Passo <span id="currentStepDisplay">1</span> de 2: Dados de acesso.</p>
            </div>
            
            <div class="step-indicator-bar">
                <div class="step-line active" id="stepIndicator1"></div>
                <div class="step-line" id="stepIndicator2"></div>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Oops!</strong> Verifique os dados informados.
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm" class="enhanced-form">
                @csrf

                <div class="form-step active" id="step1">
                    <div class="input-group">
                        <label for="name" class="input-label">Nome Completo</label>
                        <input type="text" name="name" id="name" class="enhanced-input" placeholder="Digite seu nome completo" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    </div>

                    <div class="input-group">
                        <label for="username" class="input-label">Nome de Usuário</label>
                        <input type="text" name="arroba" id="username" class="enhanced-input" placeholder="Crie um nome de usuário (ex: seu_nome)" value="{{ old('arroba') }}" required autocomplete="username">
                    </div>

                    <div class="input-group">
                        <label for="email" class="input-label">Email</label>
                        <input type="email" name="email" id="email" class="enhanced-input" placeholder="Digite seu email" value="{{ old('email') }}" required autocomplete="email">
                    </div>

                    <div class="input-group">
                        <label for="password" class="input-label">Senha</label>
                        <input type="password" name="password" id="password" class="enhanced-input" placeholder="Crie uma senha (mín. 8 caracteres)" required autocomplete="new-password">
                        <button type="button" class="password-toggle" onclick="togglePassword(this)">
                            <svg class="eye-open" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/></svg>
                        </button>
                    </div>

                    <div class="input-group">
                        <label for="password_confirmation" class="input-label">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="enhanced-input" placeholder="Confirme sua senha" required autocomplete="new-password">
                        <button type="button" class="password-toggle" onclick="togglePassword(this)">
                            <svg class="eye-open" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="eye-closed" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/></svg>
                        </button>
                    </div>
                    
                    <button type="button" class="enhanced-btn bg-rose-600 mt-4" id="nextStepBtn">
                        Próximo Passo &rarr;
                    </button>
                </div>
                
                <div class="form-step" id="step2" style="display: none;">

                    <div class="input-group">
                        <label for="bio" class="input-label">Sua Biografia (Opcional)</label>
                        <textarea name="bio" id="bio" rows="4" class="enhanced-input" placeholder="Fale um pouco sobre você..." maxlength="255">{{ old('bio') }}</textarea>
                        <small class="text-gray-500 float-right mt-1" id="bioCharCount">0/255</small>
                    </div>

                    <div class="flex gap-4 mt-4">
                        <button type="button" class="enhanced-btn bg-gray-700 flex-1" id="prevStepBtn">
                            &larr; Voltar
                        </button>
                        <button type="submit" class="enhanced-btn bg-rose-600 flex-1" id="submitBtn">
                            Criar Conta
                        </button>
                    </div>
                </div>
            </form>

            <div class="auth-links">
                <p class="text-sm text-gray-400">
                    Já tem uma conta? 
                    <a href="{{ route('login') }}" class="auth-link primary-link text-rose-600">Fazer login</a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
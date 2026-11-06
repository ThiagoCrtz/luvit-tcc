@extends('layouts.auth')
@section('title', 'Login - Luvit')

@vite(['resources/js/pages/auth/login.js'])
@section('content')

<section class="login-section">
    <div class="login-visual">
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
            <p class="brand-tagline">Filmes, jogos e pessoas com os mesmos interesses.</p>
        </div>
    </div>

    <div class="login-form-side">
        <div class="login-container">
            <div class="brand-header">
                <h1 class="welcome-title">Acesse sua conta!</h1>
                <p class="welcome-subtitle">Entre para retornar a uma jornada incrível.</p>
            </div>


            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Oops!</strong> Verifique os dados informados.
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="loginForm" class="enhanced-form">
                @csrf

                <div class="input-group">
                    <label for="email" class="input-label">Email</label>
                   
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="enhanced-input"
                        placeholder="Digite seu email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                    >
                </div>

                <div class="input-group">
                    <label for="password" class="input-label">Senha</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="enhanced-input"
                        placeholder="Digite sua senha"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <svg id="eye-open" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eye-closed" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                        </svg>
                    </button>
                </div>

                <div class="remember-section">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" name="remember" id="remember" class="custom-checkbox">
                        <label for="remember" class="text-sm text-gray-300 cursor-pointer">Lembrar de mim</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Esqueceu sua senha?</a>
                    @endif
                </div>

                <button type="submit" class="enhanced-btn bg-rose-600" id="submitBtn">
                    Entrar
                </button>
            </form>


            <div class="divider">
                <span>OU CONTINUE COM</span>
            </div>


            <div class="social-login">
                <a href="{{ route('auth.google') }}" class="social-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Google
                </a>

            </div>
            <div class="auth-links">
                <p class="text-sm text-gray-400">
                    Não tem uma conta? 
                    <a href="{{ route('register') }}" class="auth-link primary-link text-rose-600">Criar conta</a> 
                </p>
            </div>
            
            <div class="flex justify-center w-full">
                <a href="{{ route('home.index') }}" class="auth-link primary-link text-rose-600 mt-2">Voltar</a>
            </div>
        </div>
    </div>
</section>
@endsection
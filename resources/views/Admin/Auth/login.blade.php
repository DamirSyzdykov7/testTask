@extends('admin.layouts.master')

@section('title', ' - Вход')

@section('content')
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #1976d2 0%, #2196f3 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-weight: bold; font-size: 24px;">
                    A
                </div>
                <h1 class="login-title">Админ-панель</h1>
                <p class="login-subtitle">Войдите в систему</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                @if($errors->any())
                    <div style="background: #ffebee; color: #c62828; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control"
                           placeholder="admin@example.com"
                           value="{{ old('email') }}"
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Пароль</label>
                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control"
                           placeholder="••••••••"
                           required>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox"
                           id="remember"
                           name="remember"
                           style="width: 18px; height: 18px;">
                    <label for="remember" style="color: #666; font-size: 14px;">Запомнить меня</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 16px; margin-top: 20px;" id="loginBtn">
                    <span id="btnText">Войти</span>
                    <div class="loading-spinner" id="loadingSpinner"></div>
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; color: #666; font-size: 12px;">
                © {{ date('Y') }} Админ-панель. Все права защищены.
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const btnText = document.getElementById('btnText');
            const loadingSpinner = document.getElementById('loadingSpinner');

            form.addEventListener('submit', function() {
                btnText.style.display = 'none';
                loadingSpinner.style.display = 'inline-block';
                loginBtn.disabled = true;
            });
        });
    </script>
@endsection

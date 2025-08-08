@extends('layouts.app')

@section('content')

<link href="{{ asset('css/login.css') }}" rel="stylesheet">

<div class="login-container">
    <div class="login-box">
        <h2 class="login-title">ユーザー新規登録画面</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <input id="name" type="text" name="name" 
                    required autocomplete="name" autofocus placeholder="ユーザー名"
                    class="@error('username') is-invalid @enderror login-input">
                @error('name')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>


            <div class="form-group">
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    required autocomplete="email" autofocus placeholder="メールアドレス"
                    class="@error('email') is-invalid @enderror login-input">
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input id="password" type="password" name="password" required
                    autocomplete="password" placeholder="パスワード"
                    class="@error('password') is-invalid @enderror login-input">
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="password_confirmation" placeholder="パスワード(確認用)"
                    class="@error('password-check') is-invalid @enderror login-input">
                @error('password-confirmation')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="button-group">
            <button type="submit" class="btn-register">新規登録</button>
            <a href="{{ route('login') }}" class="btn-login">戻る</a>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('js/register.js') }}"></script>
@vite('resources/js/register.js')
@endsection

@extends('layouts.app')

@section('content')

<link href="{{ asset('css/login.css') }}" rel="stylesheet">

<div class="login-container">
    <div class="login-box">
        <h2 class="login-title">ユーザーログイン画面</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

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

            <div class="button-group">
                <a href="{{ route('register') }}" class="btn-register">新規登録</a>
                <button type="submit" class="btn-login">ログイン</button>
            </div>
        </form>
    </div>
</div>

@if (session('login_error'))
    <script>
        alert("{{ session('login_error') }}");
    </script>
@endif


<script src="{{ asset('js/login.js') }}"></script>
@vite('resources/js/login.js')
@endsection

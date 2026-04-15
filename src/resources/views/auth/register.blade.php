@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endsection

@section('content')
<div class="layout">
    <h1>会員登録</h1>

    <form class="form" action="/register" method="post">
        @csrf
        @if ($errors->any())
    <div style="margin:12px 0; color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <div class="content">

            <div class="input-title">
                名前
            </div>
            <div class="input-text">
                <input type="text" name="name" value="{{ old('email') }}" >
            </div>

            <div class="input-title">
                メールアドレス
            </div>
            <div class="input-text">
                <input type="text" name="email" value="{{ old('email') }}" >
            </div>

            <div class="input-title">
                パスワード
            </div>
            <div class="input-text">
                <input type="password" name="password" >
            </div>

            <div class="input-title">
                確認用パスワード
            </div>
            <div class="input-text">
                <input type="password" name="password_confirmation" >
            </div>

        </div>

        <button class="auth-button">登録する</button>
        <a class="link-center" href="/login">ログインはこちら</a>
    </form>
</div>


@endsection
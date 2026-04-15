@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endsection

@section('content')
<div class="layout">
    <h1>ログイン</h1>

    <form class="form" action="/login" method="post">
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
                メールアドレス
            </div>
            <div class="input-text">
                <input type="email" name="email" value="{{ old('email') }}" >
            </div>

            <div class="input-title">
                パスワード
            </div>
            <div class="input-text">
                <input type="password" name="password" >
            </div>

        </div>

        <button class="auth-button">ログインする</button>
        <a class="link-center" href="/register">会員登録はこちら</a>
    </form>
</div>


@endsection
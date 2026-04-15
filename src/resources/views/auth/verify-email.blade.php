@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
@endsection

@section('content')
<div class="layout">
<a href="http://localhost:8025" target="_blank">
    MailHogを開く
</a>

        <form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit">認証メールを再送</button>
</form>
</div>


@endsection
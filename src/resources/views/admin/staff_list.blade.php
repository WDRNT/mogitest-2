@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/table.css') }}" />
@endsection

@section('content')
<div class="layout">
<h1 class="title-content">スタッフ一覧</h1>

<table  class="table-small">
    <thead>
        <tr>
            <th>名前</th>
            <th>メールアドレス</th>
            <th>月次勤怠</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($users as $user)
        <tr>
            <td>
                {{ $user->name }}
            </td>

            <td>
                {{ $user->email }}
            </td>

            <td>
                <a href="/stamp_correction_request/approve/{{ $user->id }}">詳細</a>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>

</div>


@endsection
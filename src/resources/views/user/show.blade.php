@extends($layout)

@section('css')
<link rel="stylesheet" href="{{ asset('css/table.css') }}" />
<link rel="stylesheet" href="{{ asset('css/show.css') }}" />
@endsection

@section('content')
<div class="layout">
<h1 class="title-content">申請一覧</h1>

<div class="page-list">
    <ul class="list">
        <li class="list__item">
            <a href="/stamp_correction_request/list?page=waiting">承認待ち</a>
        </li>
        <li class="list__item">
            <a href="/stamp_correction_request/list?page=done">承認済み</a>
        </li>
    </ul>
</div>

<table class="table-small">
    <thead>
        <tr>
            <th>状態</th>
            <th>名前</th>
            <th>対象日時</th>
            <th>申請理由</th>
            <th>申請日時</th>
            <th>詳細</th>
        </tr>
    </thead>
    <tbody>

    @foreach($lists as $list)
        <tr>

        @if($list->status === 'pending')
            <td>
                承認待ち
            </td>
        @else
            <td>
                承認済み
            </td>
        @endif
            <td>
                {{ $user->name }}
            </td>

            <td>
                {{ $list->work_date }}
            </td>

            <td>
                {{ $list->remarks }}
            </td>

            <td>
                {{ $list->created_at->format('Y/m/d') }}
            </td>

            <td>
                @if($user->role === 'admin')
                    <a href="/stamp_correction_request/approve/{{ $list->id }}">詳細</a>
                @else
                    <a href="/attendance/detail/{{ $list->attendance->id }}">詳細</a>
                @endif
            </td>
        </tr>

    @endforeach

    </tbody>
</table>

</div>


@endsection
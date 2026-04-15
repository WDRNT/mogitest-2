@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/table.css') }}" />
<link rel="stylesheet" href="{{ asset('css/list.css') }}" />
@endsection

@section('content')
<div class="layout">
<h1 class="title-content">{{ $user->name }}さんの勤怠一覧</h1>

<h2>
    <a href="/attendance/list?month={{ $prevMonth }}">← 前の月</a>

    {{ $current->format('Y年m月') }}

    <a href="/attendance/list?month={{ $nextMonth }}">次の月 →</a>
</h2>

<table class="table-small">
    <thead>
        <tr>
            <th>日付</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
        </tr>
    </thead>
    <tbody>

            @foreach ($dates as $date)
            <tr>
                <td>
                    {{ $date['date'] }}
                </td>

                @if ($date['attendance'])
                <td>
                    {{ $date['attendance']->clock_in
                        ? \Carbon\Carbon::parse($date['attendance']->clock_in)->format('H:i')
                        : '-' }}
                </td>

                <td>
                    {{ $date['attendance']->clock_out
                        ? \Carbon\Carbon::parse($date['attendance']->clock_out)->format('H:i')
                        : '-' }}
                </td>
                @else
                    <td>-</td>
                    <td>-</td>
                @endif

                <td>
                @if ($date['attendance'])
                    {{ $date['attendance']->total_break_time }}
                @endif
                </td>

                <td>
                @if ($date['attendance'])
                    {{ $date['attendance']->work_time }}
                @endif
                </td>

                <td>
                @if ($date['attendance'])
                    <a href="/attendance/detail/{{ $date['attendance']->id }}">詳細</a>
                @else
                    詳細
                @endif
                </td>
            </tr>

            @endforeach

    </tbody>
</table>
</div>


@endsection
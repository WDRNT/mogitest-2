@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/table.css') }}" />
<link rel="stylesheet" href="{{ asset('css/list.css') }}" />
@endsection

@section('content')
<div class="layout">
<h1 class="title-content">{{ $current->format('Y年m月d日') }}の勤怠</h1>

<h2>
    <a href="/admin/attendance/list?day={{ $prevDay }}">← 前の日</a>

    {{ $current->format('Y年m月d日') }}

    <a href="/admin/attendance/list?day={{ $nextDay }}">次の日 →</a>
</h2>

<table class="table-small">
    <thead>
        <tr>
            <th>名前</th>
            <th>出勤</th>
            <th>退勤</th>
            <th>休憩</th>
            <th>合計</th>
            <th>詳細</th>
        </tr>
    </thead>
    <tbody>
    @foreach($attendances as $attendance)
        <tr>
            <td>
                {{ $attendance->user->name }}
            </td>

            <td>
                {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}
            </td>

            <td>
                {{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}
            </td>

            <td>
                {{ $attendance->total_break_time }}
            </td>

            <td>
                {{ $attendance->work_time }}
            </td>

            <td>
                <a href="/admin/attendance/{{ $attendance->id }}">詳細</a>
            </td>
        </tr>
    @endforeach

    </tbody>
</table>
</div>


@endsection
@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/table.css') }}" />
@endsection

@section('content')
<div class="layout">
<h1 class="title-content">勤怠詳細</h1>


<form action="/stamp_correction_request/approve/{{ $attendanceRequest->id }}" method="POST">
    @csrf
    <table class="table-big">
        <tr>
            <th>名前</th>
            <td>{{ $attendanceRequest->user->name }}</td>
        </tr>
        <tr>
            <th>日付</th>
            <td>{{ $attendanceRequest->work_date }}</td>
        </tr>

        <tr>
            <th>出勤・退勤</th>
            <td>
                    <input type="text" name="clock_in" value="{{ old('clock_in', $attendanceRequest->clock_in) }}"
                    {{ $attendanceRequest->attendance->isEditable() ? '' : 'disabled' }}>~

                    <input type="text" name="clock_out" value="{{ old('clock_out', $attendanceRequest->clock_out) }}"
                    {{ $attendanceRequest->attendance->isEditable() ? '' : 'disabled' }}>
            </td>
        </tr>

        @foreach ($attendanceRequest->breaks as $break)
        <tr>
            <th>
                休憩{{ $loop->iteration === 1 ? '' : $loop->iteration }}
            </th>

            <td>
                <input type="text"
                    name="breaks[{{ $loop->index }}][break_in]"
                    value="{{ old("breaks.$loop->index.break_in", $break->break_in
                    ? \Carbon\Carbon::parse($break->break_in)->format('H:i')
                    : ''
                    ) }}"
                    {{ $attendanceRequest->attendance->isEditable() ? '' : 'disabled' }}~

                <input type="text"
                    name="breaks[{{ $loop->index }}][break_out]"
                    value="{{ old("breaks.$loop->index.break_out", $break->break_out? \Carbon\Carbon::parse($break->break_out)->format('H:i')
                    : ''
                    ) }}"
                    {{ $attendanceRequest->attendance->isEditable() ? '' : 'disabled' }}>
            </td>
        </tr>
        @endforeach

        <tr>
            <th>
                休憩{{ count($attendanceRequest->breaks) + 1 === 1 ? '' : count($attendanceRequest->breaks) + 1 }}
            </th>

            <td>
                <input type="text"
                    name="breaks[{{ count($attendanceRequest->breaks) }}][break_in]"
                    {{ $attendanceRequest->attendance->isEditable() ? '' : 'disabled' }}>~

                <input type="text"
                    name="breaks[{{ count($attendanceRequest->breaks) }}][break_out]"
                    {{ $attendanceRequest->attendance->isEditable() ? '' : 'disabled' }}>
            </td>
        </tr>


        <tr>
            <th>備考</th>
            <td>
                <textarea name="remarks" {{ $attendanceRequest->attendance->isEditable() ? '' : 'disabled' }}></textarea>
            </td>
        </tr>
    </table>

    @if($attendanceRequest->status === 'pending')
        <button class="button-content" type="submit">承認</button>
    @else
        <button class="button-content" type="submit" disabled>承認済み</button>
    @endif
</form>

</div>


@endsection
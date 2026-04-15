@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/table.css') }}" />
@endsection

@section('content')
<div class="layout">
<h1 class="title-content">勤怠詳細</h1>


<form action="/attendance/detail/{{ $attendance->id }}" method="POST">
    @csrf
    <table class="table-big">
        <tr>
            <th>名前</th>
            <td>{{ $attendance->user->name }}</td>
        </tr>
        <tr>
            <th>日付</th>
            <td>{{ $attendance->work_date }}</td>
        </tr>

        <tr>
            <th>出勤・退勤</th>
            <td>
                    <input type="text" name="clock_in" value="{{ old('clock_in', $attendance->clock_in) }}"
                    {{ $attendance->isEditable() ? '' : 'disabled' }}>~

                    <input type="text" name="clock_out" value="{{ old('clock_out', $attendance->clock_out) }}"
                    {{ $attendance->isEditable() ? '' : 'disabled' }}>
            </td>
        </tr>

        @foreach ($attendance->breaks as $break)
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
                    {{ $attendance->isEditable() ? '' : 'disabled' }}>~

                <input type="text"
                    name="breaks[{{ $loop->index }}][break_out]"
                    value="{{ old("breaks.$loop->index.break_out", $break->break_out? \Carbon\Carbon::parse($break->break_out)->format('H:i')
                    : ''
                    ) }}"
                    {{ $attendance->isEditable() ? '' : 'disabled' }}>
            </td>
        </tr>
        @endforeach

        <tr>
            <th>
                休憩{{ count($attendance->breaks) + 1 === 1 ? '' : count($attendance->breaks) + 1 }}
            </th>

            <td>
                <input type="text"
                    name="breaks[{{ count($attendance->breaks) }}][break_in]"
                    {{ $attendance->isEditable() ? '' : 'disabled' }}>~

                <input type="text"
                    name="breaks[{{ count($attendance->breaks) }}][break_out]"
                    {{ $attendance->isEditable() ? '' : 'disabled' }}>
            </td>
        </tr>


        <tr>
            <th>備考</th>
            <td>
                <textarea name="remarks" {{ $attendance->isEditable() ? '' : 'disabled' }}></textarea>
            </td>
        </tr>
    </table>

    @if ($attendance->isEditable())
        <button class="button-content" type="submit">修正</button>
    @else
        <p class="color-red">*承認待ちのため編集できません。</p>
    @endif
</form>

</div>


@endsection
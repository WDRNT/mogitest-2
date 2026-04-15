@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}" />
@endsection

@section('content')
<div class="layout">
    <div class="text-center">{{ $status_view }}</div>
    <h1 class="text-center">{{ now()->isoFormat('Y年M月D日(ddd)') }}</h1>
    <h1 class="text-center">{{ now()->format('H:i') }}</h1>

    {{-- 勤務外 --}}
    @if($status === 'off-work')
    <form method="POST" action="/attendance">
        @csrf
        <button class="button button-white" type="submit" name="action" value="clock_in">出勤</button>
    </form>
    @endif


    {{-- 出勤中 --}}
    @if($status === 'working')
    <div class="button-content">
        <form method="POST" action="/attendance">
            @csrf
            <button class="button button-white" type="submit" name="action" value="break_in">休憩入</button>
        </form>

        <form method="POST" action="/attendance">
            @csrf
            <button class="button button-black" type="submit" name="action" value="clock_out">退勤</button>
        </form>
    </div>
    @endif


    {{-- 休憩中 --}}
    @if($status === 'on-break')
    <form method="POST" action="/attendance">
        @csrf
        <button class="button button-white" type="submit" name="action" value="break_out">休憩戻</button>
    </form>
    @endif


    {{-- 退勤済 --}}
    @if($status === 'done')
    <p>お疲れ様でした。</p>
    @endif
</div>


@endsection
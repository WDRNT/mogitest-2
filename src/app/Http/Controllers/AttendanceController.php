<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\AttendanceRequest;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request){
        $user = auth()->user();
        $today = now()->toDateString();

        $attendance = Attendance::with('breaks',)
            ->where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->first();

        $status = 'off-work';
        $status_view = '勤務外';

        if ($attendance) {
            $latestBreak = $attendance->breaks->last();

            if ($attendance->clock_out) {
                $status = 'done';
                $status_view = '退勤済';
            } elseif ($latestBreak && is_null($latestBreak->break_out)) {
                $status = 'on-break';
                $status_view = '休憩中';
            } else {
                $status = 'working';
                $status_view = '出勤中';
            }
        }
        return view('user.index', compact(
            'status',
            'status_view',
            'attendance',));
    }

    public function storeAttendance(Request $request)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'clock_in':
                return $this->clockIn();

            case 'break_in':
                return $this->breakIn();

            case 'break_out':
                return $this->breakOut();

            case 'clock_out':
                return $this->clockOut();

            default:
                abort(400);
        }
    }

    private function clockIn()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        Attendance::create([
            'user_id'   => $user->id,
            'work_date' => $today,
            'clock_in'  => now(),
        ]);

        return back();
    }

    private function breakIn(){
        $user = auth()->user();
        $today = now();

        $attendance = Attendance::with('breaks')
            ->where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->first();

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'break_in' => $today,
        ]);

        return back();
    }

    private function breakOut(){
        $user = auth()->user();
        $today = now();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->first();

        $breakTime = BreakTime::where('attendance_id', $attendance->id)
            ->whereNull('break_out')
            ->latest()
            ->first();

        if (!$breakTime) {
            return back();
        }

        $breakTime->break_out = now();
        $breakTime->save();

        return back();
    }

    private function clockOut(){
        $user = auth()->user();
        $today = now();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->first();

        $attendance->clock_out = now();
        $attendance->save();

        return back();
    }

    public function list(Request $request)
    {
        $user = auth()->user();

        $currentMonth = $request->input('month', now()->format('Y-m'));

        $current = Carbon::parse($currentMonth);

        $start = $current->copy()->startOfMonth();
        $end   = $current->copy()->endOfMonth();

        $prevMonth = $current->copy()->subMonth()->format('Y-m');
        $nextMonth = $current->copy()->addMonth()->format('Y-m');

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('work_date', [$start, $end])
            ->get()
            ->keyBy(function ($item) {
                return \Carbon\Carbon::parse($item->work_date)->toDateString();
            });

        $dates = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dateStr = $date->toDateString();

            $dates[] = [
                'date' => $dateStr,
                'attendance' => $attendances[$dateStr] ?? null,
            ];
        }

        return view('user.list', compact(
            'dates',
            'current',
            'prevMonth',
            'nextMonth'
        ));
    }

    public function edit($id){
        $attendance = Attendance::with('breaks',)
            ->find($id);

        $request = $attendance->attendanceRequests()
            ->latest()
            ->first();

        return view('user.edit', compact('attendance', 'request'));
    }



}

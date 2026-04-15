<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakTime;
use App\Models\User;
use Carbon\Carbon;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $currentDay = $request->input('day', now()->format('Y-m-d'));

        $current = Carbon::parse($currentDay);

        $prevDay = $current->copy()->subDay()->format('Y-m-d');
        $nextDay = $current->copy()->addDay()->format('Y-m-d');

        $attendances = Attendance::whereDate('work_date', $current)
            ->get();

        return view('admin.index', compact(
            'current',
            'prevDay',
            'nextDay',
            'attendances'
        ));
    }

    public function edit($id)
    {
        $attendance = Attendance::with('breaks',)
            ->find($id);

        $request = $attendance->attendanceRequests()
            ->latest()
            ->first();

        return view('admin.edit', compact('attendance', 'request'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::with('breaks')->findOrFail($id);

        $attendance->update([
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'remarks' => $request->remarks,
        ]);

        if ($request->has('breaks')){
            $existingBreaks = $attendance->breaks->values();

            foreach ($request->breaks as $index => $break) {
                if (empty($break['break_in']) && empty($break['break_out'])) {
                    continue;
                }

                $breakIn = !empty($break['break_in'])
                    ? Carbon::parse($attendance->work_date . ' ' . $break['break_in'])
                    : null;

                $breakOut = !empty($break['break_out'])
                    ? Carbon::parse($attendance->work_date . ' ' . $break['break_out'])
                    : null;

                $existingBreak = $existingBreaks[$index] ?? null;

                if ($existingBreak) {
                    $existingBreak->update([
                        'break_in' => $breakIn,
                        'break_out' => $breakOut,
                    ]);
                } else {
                    $attendance->breaks()->create([
                        'break_in' => $breakIn,
                        'break_out' => $breakOut,
                    ]);
                }
            }
        }
    }

    public function staff_list()
    {
        $users = User::where('role', 'user')->get();

        return view('admin.staff_list', compact('users'));
    }

    public function show(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

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

        return view('admin.staff_attendance', compact(
            'user',
            'dates',
            'current',
            'prevMonth',
            'nextMonth'
        ));

    }

    public function exportCsv(Request $request)
    {
        $currentMonth = $request->input('month', now()->format('Y-m'));
        $current = Carbon::parse($currentMonth);

        $start = $current->copy()->startOfMonth();
        $end   = $current->copy()->endOfMonth();

        $attendances = Attendance::with(['breaks', 'user'])
            ->whereBetween('work_date', [$start, $end])
            ->orderBy('work_date')
            ->get();

        $fileName = 'attendance_' . $current->format('Y_m') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($attendances) {
            $handle = fopen('php://output', 'w');

            // BOM（Excel対策）
            fwrite($handle, "\xEF\xBB\xBF");

            // ヘッダー
            fputcsv($handle, ['名前', '日付', '出勤', '退勤', '休憩', '合計']);

            foreach ($attendances as $attendance) {
                fputcsv($handle, [
                    $attendance->user->name ?? '',
                    Carbon::parse($attendance->work_date)->format('Y-m-d'),
                    $attendance->clock_in
                        ? Carbon::parse($attendance->clock_in)->format('H:i')
                        : '',
                    $attendance->clock_out
                        ? Carbon::parse($attendance->clock_out)->format('H:i')
                        : '',
                    $attendance->total_break_time ?? '',
                    $attendance->work_time ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

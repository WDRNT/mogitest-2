<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\BreakRequest;
use Carbon\Carbon;

class AttendanceRequestController extends Controller
{
    public function store(Request $request, $id)
    {
        $attendance = Attendance::with('breaks')->findOrFail($id);


        $attendanceRequest = AttendanceRequest::create([
            'attendance_id' => $attendance->id,
            'user_id' => auth()->id(),
            'work_date' => $attendance->work_date,
            'clock_in' => $request->clock_in
                ? Carbon::parse($attendance->work_date . ' ' . $request->clock_in)
                : null,
            'clock_out' => $request->clock_out
                ? Carbon::parse($attendance->work_date . ' ' . $request->clock_out)
                : null,
            'remarks' => $request->remarks,
        ]);

        if ($request->has('breaks')) {
            foreach ($request->breaks as $break) {
                if (!empty($break['break_in']) || !empty($break['break_out'])) {
                    BreakRequest::create([
                        'attendance_request_id' => $attendanceRequest->id,
                        'break_in' => !empty($break['break_in'])
                            ? Carbon::parse($attendance->work_date . ' ' . $break['break_in'])
                            : null,
                        'break_out' => !empty($break['break_out'])
                            ? Carbon::parse($attendance->work_date . ' ' . $break['break_out'])
                            : null,
                    ]);
                }
            }
        };

        return back();

    }

    public function show(Request $request){
        $page = $request->query('page', 'waiting');

        $user =auth()->user();

        $layout = $user->role === 'admin' ? 'layouts.admin' : 'layouts.app';

        if($user->role === 'admin'){
            if($page === 'done'){
                $lists = AttendanceRequest::where('status', 'approved')->get();
            }else{
                $lists = AttendanceRequest::where('status', 'pending')->get();
            }
        }else{
            if($page === 'done'){
                $lists = AttendanceRequest::where('user_id', $user->id)
                    ->where('status', 'approved')->get();
            }else{
                $lists = AttendanceRequest::where('user_id', $user->id)
                    ->where('status', 'pending')->get();
            }
        }


        return view('user.show', compact(
            'lists',
            'page',
            'user',
            'layout'
            ));
    }

    public function edit($id)
    {
        $attendanceRequest = AttendanceRequest::with('breaks', 'attendance')
            ->find($id);



        return view('admin.attendance_approval', compact('attendanceRequest'));
    }

    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {

            $attendanceRequest = AttendanceRequest::with('attendance')->findOrFail($id);

            $attendance = $attendanceRequest->attendance;

            $attendance->update([
                'clock_in'  => $attendanceRequest->clock_in,
                'clock_out' => $attendanceRequest->clock_out,
            ]);

            $attendance->breaks()->delete();

            if ($request->breaks) {
                foreach ($request->breaks as $break) {

                    if (empty($break['break_in']) || empty($break['break_out'])) {
                        continue;
                    }

                    $attendance->breaks()->create([
                        'break_in'  => $break['break_in'],
                        'break_out' => $break['break_out'],
                    ]);
                }
            }

            $attendanceRequest->update([
                'status' => 'approved'
            ]);
        });

        return back();


    }
}

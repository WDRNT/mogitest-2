<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BreakTime;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in',
    ];

    public function getTotalBreakSecondsAttribute(){
        $total = 0;

        foreach ($this->breaks as $break) {
            if ($break->break_in && $break->break_out) {
                $total += \Carbon\Carbon::parse($break->break_in)
                    ->diffInSeconds(\Carbon\Carbon::parse($break->break_out));
            }
        }

        return $total;
    }

    public function getTotalBreakTimeAttribute()
    {
        $hours = floor($this->total_break_seconds / 3600);
        $minutes = floor(($this->total_break_seconds % 3600) / 60);

        return str_pad($hours, 2, '0', STR_PAD_LEFT)
            . ':' .
            str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }

    public function getWorkSecondsAttribute()
    {
        if (!$this->clock_in || !$this->clock_out) {
            return 0;
        }

        return \Carbon\Carbon::parse($this->clock_out)
            ->diffInSeconds(\Carbon\Carbon::parse($this->clock_in))
            - $this->total_break_seconds;
    }

    public function getWorkTimeAttribute()
    {
        $hours = floor($this->work_seconds / 3600);
        $minutes = floor(($this->work_seconds % 3600) / 60);

        return str_pad($hours, 2, '0', STR_PAD_LEFT)
            . ':' .
            str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }

    public function breaks()
    {
        return $this->hasMany(BreakTime::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function attendanceRequests()
    {
        return $this->hasMany(AttendanceRequest::class);
    }

    public function latestRequest()
    {
        return $this->hasOne(AttendanceRequest::class)->latestOfMany();
    }

        public function isEditable()
    {
        return ! $this->latestRequest
            || $this->latestRequest->status !== 'pending';
    }

}

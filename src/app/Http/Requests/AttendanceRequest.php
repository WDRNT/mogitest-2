<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'clock_in'  => ['required', 'date_format:H:i'],
            'clock_out' => ['required', 'date_format:H:i'],

            'breaks.*.break_in'  => ['nullable', 'date_format:H:i'],
            'breaks.*.break_out' => ['nullable', 'date_format:H:i'],

            'remarks' => ['required'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $clockIn  = $this->clock_in;
            $clockOut = $this->clock_out;

            // ① 出勤・退勤チェック
            if ($clockIn && $clockOut && $clockIn >= $clockOut) {
                $validator->errors()->add('clock_in', '出勤時間もしくは退勤時間が不適切な値です');
            }

            // ②③ 休憩チェック
            if ($this->breaks) {
                foreach ($this->breaks as $index => $break) {

                    $breakIn  = $break['break_in'] ?? null;
                    $breakOut = $break['break_out'] ?? null;

                    // ② 休憩開始が勤務時間外
                    if ($breakIn && (
                        $breakIn < $clockIn ||
                        $breakIn > $clockOut
                    )) {
                        $validator->errors()->add(
                            "breaks.$index.break_in",
                            '休憩時間が不適切な値です'
                        );
                    }

                    // ③ 休憩終了が退勤より後
                    if ($breakOut && $breakOut > $clockOut) {
                        $validator->errors()->add(
                            "breaks.$index.break_out",
                            '休憩時間もしくは退勤時間が不適切な値です'
                        );
                    }
                }
            }
        });
    }

    public function messages()
    {
        return [
            'remarks.required' => '備考を記入してください',
        ];
    }
}

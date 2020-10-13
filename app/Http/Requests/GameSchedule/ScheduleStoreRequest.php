<?php

namespace App\Http\Requests\GameSchedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ScheduleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return validateAccess(Auth::user(),2); 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'number_win' => 'required|max:5',
            'number_win2' => 'max:5',
            'date' => 'required|after:yesterday',
            'game_id' => 'required',
        ];
    }
}
